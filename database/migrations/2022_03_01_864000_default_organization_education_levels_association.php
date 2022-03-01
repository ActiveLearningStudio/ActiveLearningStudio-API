<?php

use Illuminate\Database\Migrations\Migration;

class DefaultOrganizationEducationLevelsAssociation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => DefaultEducationLevelsSeeder::class,
            '--force' => true
        ]);

        \Artisan::call('db:seed', [
            '--class' => ActivityEducationLevelsAssociationSeeder::class,
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
