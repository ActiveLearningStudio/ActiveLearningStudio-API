<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKalturaApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kaltura_api_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id')->nullable()->default(1);
            $table->string('partner_id', 50);
            $table->string('sub_partner_id', 50)->nullable()->default(null);
            $table->string('name', 100)->nullable()->default(null);
            $table->string('email', 150)->nullable()->default(null);
            $table->string('expiry', 150)->nullable()->default('86400');
            $table->enum('session_type', [0, 2])->nullable()->default(0);
            $table->longText('admin_secret');
            $table->longText('user_secret')->nullable()->default(null);
            $table->longText('privileges')->nullable()->default('*');
            $table->longText('description')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
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
        Schema::dropIfExists('kaltura_api_settings');
    }
}
