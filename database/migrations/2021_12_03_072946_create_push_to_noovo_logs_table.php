<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushToNoovoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_to_noovo_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->string('noovo_company_id')->nullable();
            $table->string('noovo_company_title')->nullable();
            $table->string('noovo_team_id')->nullable();
            $table->string('noovo_team_title')->nullable();
            $table->string('projects')->nullable();
            $table->string('response')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_to_noovo_logs');
    }
}
