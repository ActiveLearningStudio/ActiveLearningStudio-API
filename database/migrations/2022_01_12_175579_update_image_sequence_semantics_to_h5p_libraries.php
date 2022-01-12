<?php

use Illuminate\Database\Migrations\Migration;

class UpdateImageSequencingSemanticsToH5PLibraries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => H5PImageSequencingLibSubmitButtonSeeder::class
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
