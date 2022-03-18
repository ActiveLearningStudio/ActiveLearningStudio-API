<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdIntoDefaultSsoIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default_sso_integration_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default_sso_integration_settings', function (Blueprint $table) {
            $table->dropColumn(['role_id']);
        });
    }
}
