<?php

use Database\Seeders\UpdateCurrikiIVH5PLibrarySemanticSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateCurrikiIVH5PLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateCurrikiIVH5PLibrarySemanticSeeder::class,
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
