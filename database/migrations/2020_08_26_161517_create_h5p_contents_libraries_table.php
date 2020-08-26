<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pContentsLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_contents_libraries', function (Blueprint $table) {
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('h5p_contents');
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('h5p_libraries');
            $table->string('dependency_type', 31);
            $table->unsignedInteger('weight')->default(0);
            $table->boolean('drop_css');
            $table->primary(['content_id', 'library_id', 'dependency_type'], 'fk_primary');
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
        Schema::dropIfExists('h5p_contents_libraries');
    }
}
