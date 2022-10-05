<?php

use Illuminate\Database\Migrations\Migration;

class UpdateAdvancedFibSemanticsToH5PLibrariesV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => H5PAdvancedFibSubmitButtonSeeder::class,
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
