<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\C2E\Publisher\C2ePublisherOrganizationPermissionTypeSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => C2ePublisherOrganizationPermissionTypeSeeder::class,
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
        //
    }
};
