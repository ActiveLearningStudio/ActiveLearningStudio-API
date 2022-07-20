<?php

use Illuminate\Database\Migrations\Migration;

class UpdateCivSemanticsFunctionalityToH5pLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateCIVSemanticsFunctionalityToH5PLibrariesSeeder::class,
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
