<?php


use Database\Seeders\UpdateEpubColumnV2Seeder;
use Illuminate\Database\Migrations\Migration;

class UpdateEpubColumnV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateEpubColumnV2Seeder::class,
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
