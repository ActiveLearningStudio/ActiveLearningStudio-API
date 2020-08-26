<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pContentsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_contents_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('h5p_contents');
            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('h5p_tags');
            $table->primary(['content_id', 'tag_id']);
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
        Schema::dropIfExists('h5p_contents_tags');
    }
}
