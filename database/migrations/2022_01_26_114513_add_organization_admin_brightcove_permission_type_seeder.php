<?php

use Illuminate\Database\Migrations\Migration;

class AddOrganizationAdminBrightcovePermissionTypeSeeder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => OrganizationAdminPanelBrightCovePermissionTypeSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
