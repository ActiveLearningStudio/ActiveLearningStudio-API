<?php

use Illuminate\Database\Migrations\Migration;

class UpdateH5pEditorCurrikiIvToH5pLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateH5pEditorCurrikiIVToH5pLibrariesSeeder::class,
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
