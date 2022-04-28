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
            $table->foreignId('independent_activity_id')->constrained();
            $table->foreignId('author_tag_id')->constrained();
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
