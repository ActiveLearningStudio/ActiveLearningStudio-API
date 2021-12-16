<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileAppH5pResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_app_h5p_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            // $table->foreign('content_id')->references('id')->on('h5p_contents');
            $table->string('email')->unique();
            $table->unsignedInteger('score')->unsigned();
            $table->unsignedInteger('max_score')->unsigned();
            $table->unsignedInteger('opened')->unsigned();
            $table->unsignedInteger('finished')->unsigned();
            $table->unsignedInteger('time')->unsigned();
            $table->index(['content_id'], 'content_user');
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
        Schema::dropIfExists('mobile_app_h5p_results');
    }
}
