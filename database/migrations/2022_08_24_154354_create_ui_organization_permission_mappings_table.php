<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiOrganizationPermissionMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_organization_permission_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('ui_module_permission_id');
            $table->foreign('ui_module_permission_id')->references('id')->on('ui_module_permissions');
            $table->unsignedBigInteger('organization_permission_type_id');
            $table->foreign('organization_permission_type_id')->references('id')->on('organization_permission_types');
            $table->unique(['ui_module_permission_id', 'organization_permission_type_id']);
            $table->timestamps();
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
        Schema::dropIfExists('ui_organization_permission_mappings');
    }
}
