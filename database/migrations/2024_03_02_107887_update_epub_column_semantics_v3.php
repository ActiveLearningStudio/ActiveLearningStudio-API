<?php

use Database\Seeders\UpdateEpubColumnSemanticsV3Seeder;
use Illuminate\Database\Migrations\Migration;

class UpdateEpubColumnSemanticsV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateEpubColumnSemanticsV3Seeder::class,
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
