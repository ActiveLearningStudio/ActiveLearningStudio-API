<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_libraries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 127);
            $table->string('title');
            $table->unsignedInteger('major_version');
            $table->unsignedInteger('minor_version');
            $table->unsignedInteger('patch_version');
            $table->unsignedInteger('runnable')->index('runnable');
            $table->unsignedInteger('restricted')->default(0);
            $table->unsignedInteger('fullscreen');
            $table->string('embed_types');
            $table->string('preloaded_js', 65535)->nullable();
            $table->string('preloaded_css', 65535)->nullable();
            $table->string('drop_library_css', 65535)->nullable();
            $table->string('semantics', 65535);
            $table->string('tutorial_url', 1023);
            $table->unsignedInteger('has_icon')->default(0);
            $table->index(['name', 'major_version', 'minor_version', 'patch_version']);
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
        Schema::dropIfExists('h5p_libraries');
    }
}
