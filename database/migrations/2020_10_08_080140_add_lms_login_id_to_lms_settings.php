<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLmsLoginIdToLmsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lms_settings', function (Blueprint $table) {
            $table->string('lms_login_id')->nullable()->after('lti_client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lms_settings', function (Blueprint $table) {
            $table->dropColumn(['lms_login_id']);
        });
    }
}
