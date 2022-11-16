<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLtiToolSettingsStatusOrganizationMediaSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_media_sources', function (Blueprint $table) {
            $table->boolean('lti_tool_settings_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_media_sources', function (Blueprint $table) {
            $table->dropColumn(['lti_tool_settings_status']);
        });
    }
}
