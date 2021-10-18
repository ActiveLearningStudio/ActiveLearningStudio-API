<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLtiToolSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti_tool_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id')->nullable()->default(1);            
            $table->string('tool_name');
            $table->longText('tool_url');
            $table->string('tool_domain');
            $table->string('lti_version', 20);
            $table->string('tool_consumer_key')->nullable()->default(null);
            $table->string('tool_secret_key')->nullable()->default(null);
            $table->longText('tool_description')->nullable()->default(null);
            $table->longText('tool_custom_parameter')->nullable()->default(null);
            $table->longText('tool_content_selection_url');
            $table->string('tool_client_id')->nullable()->default(null);
            $table->unsignedBigInteger('tool_proxy_id')->nullable()->default(null);
            $table->longText('tool_enabled_capability')->nullable()->default(null);            
            $table->longText('tool_icon')->nullable()->default(null);
            $table->longText('tool_secure_icon')->nullable()->default(null);            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti_tool_settings');
    }
}
