<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndependentActivityAuthorTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('independent_activity_author_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('independent_activity_id');
            $table->foreign('independent_activity_id')->references('id')->on('independent_activities');
            $table->unsignedBigInteger('author_tag_id');
            $table->foreign('author_tag_id')->references('id')->on('author_tags');
            $table->unique(['independent_activity_id', 'author_tag_id']);
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
        Schema::dropIfExists('independent_activity_author_tag');
    }
}
