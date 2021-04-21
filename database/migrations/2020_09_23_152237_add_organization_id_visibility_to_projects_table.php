<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationIdVisibilityToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->default(null);
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('organization_visibility_type_id')->nullable()->default(null);
            $table->foreign('organization_visibility_type_id')->references('id')->on('organization_visibility_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('organization_id');
            $table->dropColumn('organization_visibility_type_id');
        });
    }
}
