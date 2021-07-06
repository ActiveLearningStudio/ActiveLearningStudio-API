<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositeUniqueIndexOnOrganizationRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_role_permissions', function (Blueprint $table) {
            $table->unique(['organization_role_type_id', 'organization_permission_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_role_permissions', function (Blueprint $table) {
            $table->dropUnique(['organization_role_type_id', 'organization_permission_type_id']);
        });
    }
}
