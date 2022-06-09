<?php

use Illuminate\Database\Migrations\Migration;

class CreateIndependentActivitiesOrganizationPermissionTypeSeederMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => IndependentActivitiesOrganizationPermissionTypeSeeder::class,
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
}
