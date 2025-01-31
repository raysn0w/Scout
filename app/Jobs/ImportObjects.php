<?php

namespace App\Jobs;

use App\LdapDomain;
use App\LdapObject;
use LdapRecord\Models\Model;
use Illuminate\Support\Facades\Bus;

class ImportObjects
{
    /**
     * The LDAP domain to import objects upon.
     *
     * @var LdapDomain
     */
    protected $domain;

    /**
     * The guids of the LDAP objects synchronized.
     *
     * @var array
     */
    protected $guids = [];

    /**
     * Create a new job instance.
     *
     * @param LdapDomain $domain
     */
    public function __construct(LdapDomain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {
        $this->import();

        // Soft-delete all LDAP objects not imported.
        LdapObject::whereNotIn('guid', $this->guids)->delete();

        return count($this->guids);
    }

    /**
     * Import the LDAP objects on the given connection.
     *
     * @param Model|null      $model
     * @param LdapObject|null $parent
     */
    protected function import(Model $model = null, LdapObject $parent = null)
    {
        $this->query($model)->each(function (Model $child) use ($model, $parent) {
            /** @var LdapObject $object */
            $object = Bus::dispatch(new SyncObject($this->domain, $child, $parent));

            $this->guids[] = $object->guid;

            // If the object is a container, we will import its descendants.
            if ($object->type == 'container') {
                $this->import($child, $object);
            }
        });
    }

    /**
     * Queries the LDAP directory.
     *
     * If an entry is supplied, it will query leaf LDAP entries.
     *
     * @param Model|null $model
     *
     * @return \LdapRecord\Query\Collection
     */
    protected function query(Model $model = null)
    {
        $query = $model ?
            $model->in($model->getDn()) :
            $this->domain->getLdapModel();

        if ($filter = $this->domain->filter) {
            $query = $query->rawFilter($filter);
        }

        return $query->listing()
            ->select('*')
            ->paginate(1000);
    }
}
