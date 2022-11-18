<?php

use Illuminate\Database\Migrations\Migration;

class DefaultAllowedVisibilityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        \Artisan::call('db:seed', [
            '--class' => DefaultAllowedVisibilityTypesSeeder::class,
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

    }
}
