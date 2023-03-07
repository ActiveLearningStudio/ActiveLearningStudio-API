<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\AddLTIToolTypeColumnRecordToLtiToolSettingsTableSeeder;

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
            '--class' => AddLTIToolTypeColumnRecordToLtiToolSettingsTableSeeder::class,
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
