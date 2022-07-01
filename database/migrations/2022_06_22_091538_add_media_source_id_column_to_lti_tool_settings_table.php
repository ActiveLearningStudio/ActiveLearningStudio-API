<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaSourceIdColumnToLtiToolSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lti_tool_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('media_source_id')->nullable()->default(null);
            $table->foreign('media_source_id')->references('id')->on('media_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lti_tool_settings', function (Blueprint $table) {
            $table->dropColumn(['media_source_id']);
        });
    }
}
