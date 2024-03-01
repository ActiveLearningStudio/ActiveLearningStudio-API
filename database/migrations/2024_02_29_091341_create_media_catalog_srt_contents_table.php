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
        Schema::create('media_catalog_srt_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_catalog_api_setting_id');
            $table->string('video_id');
            $table->longText('content');
            $table->foreign('media_catalog_api_setting_id')->references('id')->on('media_catalog_api_settings');
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
        Schema::dropIfExists('media_catalog_srt_contents');
    }
};
