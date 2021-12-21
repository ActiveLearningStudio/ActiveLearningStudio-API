<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleClassFlagsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('gcr_project_visibility')->nullable()->default(true);
            $table->boolean('gcr_playlist_visibility')->nullable()->default(false);
            $table->boolean('gcr_activity_visibility')->nullable()->default(false);
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
            $table->dropColumn(['gcr_project_visibility', 'gcr_playlist_visibility', 'gcr_activity_visibility']);
        });
    }
}
