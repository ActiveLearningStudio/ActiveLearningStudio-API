<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('h5p_libraries');
            $table->string('title');
            $table->longText('parameters');
            $table->longText('filtered');
            $table->string('slug', 127);
            $table->string('embed_type', 127);
            $table->unsignedInteger('disable')->default(0);
            $table->string('content_type', 127)->nullable();
            $table->longText('authors')->nullable();
            $table->string('source', 2083)->nullable();
            $table->integer('year_from')->unsigned()->nullable();
            $table->integer('year_to')->unsigned()->nullable();
            $table->string('license', 32)->nullable();
            $table->string('license_version', 10)->nullable();
            $table->longText('license_extras')->nullable();
            $table->longText('author_comments')->nullable();
            $table->longText('changes')->nullable();
            $table->string('default_language', 32)->nullable();
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
        Schema::dropIfExists('h5p_contents');
    }
}
