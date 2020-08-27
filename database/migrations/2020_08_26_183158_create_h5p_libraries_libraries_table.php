<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pLibrariesLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_libraries_libraries', function (Blueprint $table) {
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('h5p_libraries');
            $table->unsignedBigInteger('required_library_id');
            $table->foreign('required_library_id')->references('id')->on('h5p_libraries');
            $table->string('dependency_type', 31);
            $table->timestamps();
            $table->primary(['library_id', 'required_library_id'], 'fk_primary');
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
        Schema::dropIfExists('h5p_libraries_libraries');
    }
}
