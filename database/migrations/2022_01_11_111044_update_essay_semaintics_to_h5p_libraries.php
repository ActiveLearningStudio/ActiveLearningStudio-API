<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEssaySemainticsToH5pLibraries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('h5p_libraries', function (Blueprint $table) {
            \Artisan::call('db:seed', [
                '--class' => H5PEssaysLibSubmitButtonSeeder::class
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('h5p_libraries', function (Blueprint $table) {
            //
        });
    }
}
