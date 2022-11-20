<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMsTeamAppKeysToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('msteam_client_id')->after('gcr_activity_visibility')->nullable()->default(null);
            $table->string('msteam_secret_id')->after('gcr_activity_visibility')->nullable()->default(null);
            $table->string('msteam_tenant_id')->after('gcr_activity_visibility')->nullable()->default(null);
            $table->date('msteam_secret_id_expiry')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['msteam_client_id', 'msteam_secret_id', 'msteam_tenant_id', 'msteam_secret_id_expiry']);
        });
    }
}
