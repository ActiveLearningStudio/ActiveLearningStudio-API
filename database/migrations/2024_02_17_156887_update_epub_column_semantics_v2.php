<?php


use Database\Seeders\UpdateEpubColumnSemanticsV2Seeder;
use Illuminate\Database\Migrations\Migration;

class UpdateEpubColumnSemanticsV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateEpubColumnSemanticsV2Seeder::class,
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
