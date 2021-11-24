<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeyIntoDefaultSsoRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default_sso_integration_settings', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('organization_role_types');
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
            $table->dropForeign('role_id');
        });
    }
}
