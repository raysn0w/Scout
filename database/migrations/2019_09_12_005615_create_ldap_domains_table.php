<?php

use App\LdapDomain;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLdapDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ldap_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->timestamp('synchronized_at')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('username');
            $table->string('password');
            $table->text('hosts');
            $table->string('base_dn');
            $table->string('filter')->nullable();
            $table->integer('port')->default(389);
            $table->integer('timeout')->default(5);
            $table->boolean('use_tls')->default(false);
            $table->boolean('use_ssl')->default(false);
            $table->boolean('follow_referrals')->default(false);

            $table->tinyInteger('status')->default(LdapDomain::STATUS_OFFLINE);
            $table->tinyInteger('type')->default(LdapDomain::TYPE_ACTIVE_DIRECTORY);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ldap_domains');
    }
}
