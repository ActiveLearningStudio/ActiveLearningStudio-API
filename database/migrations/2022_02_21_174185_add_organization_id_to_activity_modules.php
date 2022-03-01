<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationIdToActivityModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_layouts', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unique(['title', 'organization_id']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unique(['name', 'organization_id']);
        });

        Schema::table('author_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unique(['name', 'organization_id']);
        });

        Schema::table('education_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unique(['name', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_layouts', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });

        Schema::table('author_tags', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });

        Schema::table('education_levels', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}
