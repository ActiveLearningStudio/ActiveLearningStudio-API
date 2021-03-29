<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_role_type_id');
            $table->foreign('organization_role_type_id')->references('id')->on('organization_role_types');
            $table->unsignedBigInteger('organization_permission_type_id');
            $table->foreign('organization_permission_type_id')->references('id')->on('organization_permission_types');
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
        Schema::dropIfExists('organization_role_permissions');
    }
}
