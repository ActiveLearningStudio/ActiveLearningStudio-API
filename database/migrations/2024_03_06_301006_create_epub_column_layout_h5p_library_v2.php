<?php

use Database\Seeders\H5PAddEPubColumnLibrarySeeder;
use Illuminate\Database\Migrations\Migration;

class CreateEpubColumnLayoutH5PLibraryV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => \Database\Seeders\H5PAddEPubColumnLibrarySeederV2::class,
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
