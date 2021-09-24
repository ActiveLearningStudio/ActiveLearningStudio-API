<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultLmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_lms_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lms_settings_id');
            $table->string('lti_client_id');
            $table->foreign('lms_settings_id')->references('id')->on('lms_settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_lms_settings');
    }
}
