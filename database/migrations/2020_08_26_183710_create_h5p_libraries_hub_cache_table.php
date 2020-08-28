<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pLibrariesHubCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_libraries_hub_cache', function (Blueprint $table) {
            $table->id();
            $table->string('machine_name', 127);
            $table->unsignedInteger('major_version');
            $table->unsignedInteger('minor_version');
            $table->unsignedInteger('patch_version');
            $table->unsignedInteger('h5p_major_version')->nullable();
            $table->unsignedInteger('h5p_minor_version')->nullable();
            $table->string('title');
            $table->string('summary', 65535);
            $table->string('description', 65535);
            $table->string('icon', 511);
            $table->timestamps();
            $table->unsignedInteger('is_recommended');
            $table->unsignedInteger('popularity');
            $table->string('screenshots', 65535)->nullable();
            $table->string('license', 511)->nullable();
            $table->string('example', 511);
            $table->string('tutorial', 511)->nullable();
            $table->string('keywords', 65535)->nullable();
            $table->string('categories', 65535)->nullable();
            $table->string('owner', 511)->nullable();
            $table->index(['machine_name', 'major_version', 'minor_version', 'patch_version'], 'name_version');
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
        Schema::dropIfExists('h5p_libraries_hub_cache');
    }
}
