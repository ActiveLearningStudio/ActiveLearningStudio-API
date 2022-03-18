<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiSettingsToH5pBrightcoveVideoContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('h5p_brightcove_video_contents', function (Blueprint $table) {
            $table->string('brightcove_api_setting_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('h5p_brightcove_video_contents', function (Blueprint $table) {
            $table->dropColumn(['brightcove_api_setting_id']);
        });
    }
}
