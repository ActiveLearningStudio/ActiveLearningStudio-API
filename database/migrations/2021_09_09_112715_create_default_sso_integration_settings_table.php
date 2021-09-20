<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultSsoIntegrationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_sso_integration_settings', function (Blueprint $table) {
            $table->id();
            $table->string('lms_url');
            $table->string('lms_access_token');
            $table->string('site_name');
            $table->string('lms_name');
            $table->string('lms_access_key')->nullable();
            $table->string('lms_access_secret')->nullable();
            $table->string('description')->nullable();
            $table->string('lti_client_id')->nullable();
            $table->unsignedBigInteger('organization_id');
            $table->string('guid')->nullable();
            $table->boolean('published')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_sso_integration_settings');
    }
}
