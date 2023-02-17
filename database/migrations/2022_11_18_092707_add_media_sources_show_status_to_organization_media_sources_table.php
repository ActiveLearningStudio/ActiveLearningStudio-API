<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaSourcesShowStatusToOrganizationMediaSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_media_sources', function (Blueprint $table) {
            $table->boolean('media_sources_show_status')->default(0);
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
            $table->dropColumn(['media_sources_show_status']);
        });
    }
}
