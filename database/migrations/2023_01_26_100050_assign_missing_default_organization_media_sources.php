<?php

use Database\Seeders\AssignMissingDefaultMediaSourceSeeder;
use Illuminate\Database\Migrations\Migration;

class AssignMissingDefaultOrganizationMediaSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => AssignMissingDefaultMediaSourceSeeder::class,
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
