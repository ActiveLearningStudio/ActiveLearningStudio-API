<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_role_permissions', function (Blueprint $table) {
            $table->unsignedInteger('team_role_type_id');
            $table->foreign('team_role_type_id')->references('id')->on('team_role_types');
            $table->unsignedInteger('team_permission_type_id');
            $table->foreign('team_permission_type_id')->references('id')->on('team_permission_types');
            $table->unique(['team_role_type_id', 'team_permission_type_id']);
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
        Schema::dropIfExists('team_role_permissions');
    }
}
