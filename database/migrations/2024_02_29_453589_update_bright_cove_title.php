<?php


use Database\Seeders\UpdateBrightCoveTitleSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateBrightCoveTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateBrightCoveTitleSeeder::class,
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
