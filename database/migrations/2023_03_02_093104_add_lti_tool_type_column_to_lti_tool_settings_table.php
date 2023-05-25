<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lti_tool_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('lti_tool_type_id')->nullable()->default(null);
            $table->foreign('lti_tool_type_id')->references('id')->on('lti_tool_type');
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
            $table->dropColumn(['lti_tool_type_id']);
        });
    }
};
