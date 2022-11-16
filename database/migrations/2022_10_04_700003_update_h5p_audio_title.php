<?php

use Illuminate\Database\Migrations\Migration;

class UpdateH5pAudioTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \Artisan::call('db:seed', [
            '--class' => UpdateH5pAudioTitleSeeder::class,
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
