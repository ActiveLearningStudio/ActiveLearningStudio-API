<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsoLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_logins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('provider');
            $table->string('token')->nullable();
            $table->string('token_expired')->nullable();
            $table->string('uniqueid');
            $table->string('tool_consumer_instance_name')->nullable();
            $table->string('tool_consumer_instance_guid')->nullable();
            $table->string('custom_school')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'uniqueid', 'tool_consumer_instance_guid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_logins');
    }
}
