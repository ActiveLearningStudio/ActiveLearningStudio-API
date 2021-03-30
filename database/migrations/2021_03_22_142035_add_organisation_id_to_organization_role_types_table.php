<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToOrganizationRoleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('organization_role_types', 'organization_id')) {
            Schema::table('organization_role_types', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')->nullable()->default(1);
                $table->foreign('organization_id')->references('id')->on('organizations');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_role_types', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}
