<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pLibrariesLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_libraries_languages', function (Blueprint $table) {
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('h5p_libraries');
            $table->string('language_code', 31);
            $table->string('translation', 65535);
            $table->primary(['library_id', 'language_code'], 'fk_primary');
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
        Schema::dropIfExists('h5p_libraries_languages');
    }
}
