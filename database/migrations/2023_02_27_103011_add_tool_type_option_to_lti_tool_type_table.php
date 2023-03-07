<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\AddToolTypeOptionToLtiToolTypeTableSeeder;

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
            '--class' => AddToolTypeOptionToLtiToolTypeTableSeeder::class,
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
