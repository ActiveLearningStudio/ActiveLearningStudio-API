<?php

use Database\Seeders\UpdateIVH5PLibrarySemanticSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateIVH5PLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateIVH5PLibrarySemanticSeeder::class,
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
