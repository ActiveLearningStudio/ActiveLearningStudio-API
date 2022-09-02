<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowedOrganizationVisibilityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowed_organization_visibility_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_visibility_type_id');
            $table->foreign('organization_visibility_type_id')->references('id')->on('organization_visibility_types');
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unique(['organization_id', 'organization_visibility_type_id']);
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
        Schema::dropIfExists('allowed_organization_visibility_types');
    }
}
