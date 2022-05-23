<?php

use Illuminate\Database\Migrations\Migration;

class DefaultOrganizationSubjectsAssociation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => DefaultSubjectsSeeder::class,
            '--force' => true
        ]);

        \Artisan::call('db:seed', [
            '--class' => ActivitySubjectAssociationSeeder::class,
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
