<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToolTypeColumnToLtiToolSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lti_tool_settings', function (Blueprint $table) {
            $table->enum('tool_type', ['kaltura', 'safari_montage', 'other'])->after('lti_version')->default('other');
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
            $table->dropColumn(['tool_type']);
        });
    }
}
