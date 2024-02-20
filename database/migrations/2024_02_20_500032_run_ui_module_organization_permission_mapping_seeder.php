<?php

use Illuminate\Database\Migrations\Migration;

class RunUiModuleOrganizationPermissionMappingSeeder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UiModuleOrganizationPermissionMappingSeeder::class,
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
