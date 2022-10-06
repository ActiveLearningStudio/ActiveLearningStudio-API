<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMsTeamVisibilityFieldsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('msteam_project_visibility')->nullable()->default(true);
            $table->boolean('msteam_playlist_visibility')->nullable()->default(false);
            $table->boolean('msteam_activity_visibility')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['msteam_project_visibility', 'msteam_playlist_visibility', 'msteam_activity_visibility']);
        });
    }
}
