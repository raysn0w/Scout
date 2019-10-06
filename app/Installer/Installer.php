<?php

namespace App\Installer;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class Installer
{
    /**
     * Whether the installer has just performed initial preparation.
     *
     * @var bool
     */
    public $initialPreparation = false;

    /**
     * The installation cache key.
     *
     * @var string
     */
    protected $key = 'scout.installed';

    /**
     * Perform the install.
     *
     * @param array $data
     *
     * @throws Exception
     */
    public function install(array $data)
    {
        $stub = File::get($this->getEnvStubPath());

        try {
            $this->configureDatabase($data);

            // Delete the stub.
            File::delete($this->getEnvStubPath());
        } catch (Exception $ex) {
            $this->clearCache();

            // Restore the stub.
            File::put($this->getEnvStubPath(), $stub);

            // Re-throw the exception.
            throw $ex;
        }
    }

    /**
     * Determine if the application is installed.
     *
     * @return bool
     */
    public function installed()
    {
        if (Cache::get($this->key, false)) {
            return true;
        }

        // If the .env file exists and the .env.example file does
        // not exist, we will consider the application installed.
        // This ensures if the cache is flushed that we do not
        // impact a currently installed application.
        $installed = File::exists($this->getEnvFilePath()) && !File::exists($this->getEnvStubPath());

        Cache::forever($this->key, $installed);

        return false;
    }

    /**
     * Determine if the application migrations have been ran.
     *
     * @return bool
     */
    public function migrated()
    {
        try {
            return DB::table('migrations')->count() === 0;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Prepare the application for installation.
     *
     * @return void
     */
    public function prepare()
    {
        if (File::exists($this->getEnvFilePath())) {
            return;
        }

        if (!$this->createEnvFile()) {
            abort(500, 'Unable to create application .env file.');
        }

        Artisan::call('key:generate');

        $this->initialPreparation = true;
    }

    /**
     * Determine if the application installation was recently prepared.
     *
     * @return bool
     */
    public function wasRecentlyPrepared()
    {
        return $this->initialPreparation;
    }

    /**
     * The .env file path.
     *
     * @return string
     */
    public function getEnvFilePath()
    {
        return base_path('.env');
    }

    /**
     * The .env stub file path.
     *
     * @return string
     */
    public function getEnvStubPath()
    {
        return base_path('.env.stub');
    }

    /**
     * Configure the application .env file with the given data.
     *
     * @param array $data
     */
    protected function configureDatabase(array $data)
    {
        $contents = strtr(File::get($this->getEnvFilePath()), [
            '{{DB_DRIVER}}' => Arr::get($data, 'driver'),
            '{{DB_HOST}}' => Arr::get($data, 'host'),
            '{{DB_PORT}}' => Arr::get($data, 'port'),
            '{{DB_DATABASE}}' => Arr::get($data, 'database'),
            '{{DB_USERNAME}}' => Arr::get($data, 'username'),
            '{{DB_PASSWORD}}' => Arr::get($data, 'password'),
        ]);

        // Save the env configuration.
        File::put($this->getEnvFilePath(), $contents);
    }

    /**
     * Clear the application cache.
     */
    protected function clearCache()
    {
        Artisan::call('cache:clear');
    }

    /**
     * Create the application .env file.
     *
     * @return bool
     */
    protected function createEnvFile()
    {
        return File::put($this->getEnvFilePath(), File::get($this->getEnvStubPath()));
    }
}
