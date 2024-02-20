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
        Schema::create('media_catalog_api_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id')->nullable()->default(1);
            $table->unsignedBigInteger('media_source_id')->nullable()->default(null);
            $table->string('name');
            $table->string('api_setting_id')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->longText('url')->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);
            $table->string('client_key')->nullable()->default(null);
            $table->string('secret_key')->nullable()->default(null);
            $table->longText('custom_metadata')->nullable()->default(null);            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('media_source_id')->references('id')->on('media_sources');
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
        Schema::dropIfExists('media_catalog_api_settings');
    }
};
