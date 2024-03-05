<?php

use Illuminate\Database\Migrations\Migration;

class AddNewBrightcoveH5pLibraryV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => \Database\Seeders\H5PAddBrightcoveLibrarySeederV2::class,
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
