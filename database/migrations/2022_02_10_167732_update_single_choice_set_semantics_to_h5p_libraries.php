<?php

use Illuminate\Database\Migrations\Migration;

class UpdateSingleChoiceSetSemanticsToH5PLibraries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => H5PSingleChoiceSetSubmitButtonSeeder::class
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
