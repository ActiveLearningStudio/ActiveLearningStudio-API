<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationRoleUiPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_role_ui_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_role_type_id');
            $table->foreign('organization_role_type_id')->references('id')->on('organization_role_types');
            $table->unsignedBigInteger('ui_module_permission_id');
            $table->foreign('ui_module_permission_id')->references('id')->on('ui_module_permissions');
            $table->unique(['organization_role_type_id', 'ui_module_permission_id']);
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
        Schema::dropIfExists('organization_role_ui_permissions');
    }
}
