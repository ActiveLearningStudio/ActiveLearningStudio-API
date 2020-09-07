<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('lms_url');
            $table->string('lms_access_token');
            $table->string('site_name');
            $table->string('lms_name')->nullable()->default(null);
            $table->string('lms_access_key')->nullable()->default(null);
            $table->string('lms_access_secret')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('lms_settings');
    }
}
