<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pBrightcoveVideoContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_brightcove_video_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brightcove_video_id');
            $table->unsignedBigInteger('h5p_content_id');
            $table->foreign('h5p_content_id')->references('id')->on('h5p_contents');
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
        Schema::dropIfExists('h5p_brightcove_video_content');
    }
}
