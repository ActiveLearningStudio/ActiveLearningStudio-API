<?php

use Database\Seeders\AddCrossWordsInQuizH5PLibrarySeeder;
use Illuminate\Database\Migrations\Migration;

class AddCrossWordsInQuizH5PLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => AddCrossWordsInQuizH5PLibrarySeeder::class,
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
