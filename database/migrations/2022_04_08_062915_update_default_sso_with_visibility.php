<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultSsoWithVisibility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('default_sso_integration_settings', function (Blueprint $table) {
            $table->dropColumn(['published']);
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
        Schema::table('default_sso_integration_settings', function (Blueprint $table) {
            $table->boolean('published')->default(0);
            $table->dropColumn(['project_visibility', 'playlist_visibility', 'activity_visibility']);
        });
    }
}
