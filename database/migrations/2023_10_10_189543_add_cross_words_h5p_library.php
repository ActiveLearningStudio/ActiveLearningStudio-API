<?php

use Database\Seeders\H5PAddCrossWordsH5PLibrarySeeder;
use Illuminate\Database\Migrations\Migration;

class AddCrossWordsH5PLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => H5PAddCrossWordsH5PLibrarySeeder::class,
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
