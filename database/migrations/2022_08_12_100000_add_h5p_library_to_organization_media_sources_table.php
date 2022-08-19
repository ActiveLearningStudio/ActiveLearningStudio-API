<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddH5pLibraryToOrganizationMediaSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_media_sources', function (Blueprint $table) {
            $table->string('h5p_library')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_media_sources', function (Blueprint $table) {
            $table->dropColumn(['h5p_library']);
        });
    }
}
