<?php


use Database\Seeders\UpdateEpubColumnSemanticsSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateEpubColumnSemantics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateEpubColumnSemanticsSeeder::class,
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
