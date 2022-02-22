<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCssPathToBrightcoveApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brightcove_api_settings', function (Blueprint $table) {
            $table->string('css_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brightcove_api_settings', function (Blueprint $table) {
            $table->dropColumn(['css_path']);
        });
    }
}
