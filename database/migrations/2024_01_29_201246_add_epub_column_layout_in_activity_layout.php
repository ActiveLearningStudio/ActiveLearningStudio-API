<?php

use Database\Seeders\H5PAddEpubColumnLayoutInActivityLayoutSeeder;
use Illuminate\Database\Migrations\Migration;

class AddEpubColumnLayoutInActivityLayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => H5PAddEpubColumnLayoutInActivityLayoutSeeder::class,
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
