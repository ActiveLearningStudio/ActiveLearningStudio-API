<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pElasticsearchFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_elasticsearch_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('h5p_libraries');
            $table->string('name');
            $table->string('type');
            $table->string('label');
            $table->text('description');
            $table->string('path');
            $table->boolean('indexed');
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
        Schema::dropIfExists('h5p_elasticsearch_fields');
    }
}
