<?php

use Database\Seeders\UpdateBrightcoveIVH5PLibrarySemanticSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateBrightcoveIVH5PLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateBrightcoveIVH5PLibrarySemanticSeeder::class,
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
