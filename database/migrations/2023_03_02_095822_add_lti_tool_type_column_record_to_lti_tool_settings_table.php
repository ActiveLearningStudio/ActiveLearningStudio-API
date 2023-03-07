<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\AddLtiToolTypeColumnRecordToLtiToolSettingsTableSeeder;

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
            '--class' => AddLtiToolTypeColumnRecordToLtiToolSettingsTableSeeder::class,
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
