<?php

use Illuminate\Database\Migrations\Migration;

class AssignDefaultAdminRolePermissionsToSuborganizationAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => AssignDefaultAdminRolePermissionsToSuborganizationsAdminsSeeder::class,
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
