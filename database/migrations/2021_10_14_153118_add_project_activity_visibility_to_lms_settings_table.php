<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectActivityVisibilityToLmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_settings', function (Blueprint $table) {
            $table->boolean('project_visibility')->nullable()->default(false);
            $table->boolean('playlist_visibility')->nullable()->default(false);
            $table->boolean('activity_visibility')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lms_settings', function (Blueprint $table) {
            $table->dropColumn(['project_visibility', 'playlist_visibility', 'activity_visibility']);
        });
    }
}
