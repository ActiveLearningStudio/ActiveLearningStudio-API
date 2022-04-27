<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndependentActivityEducationLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('independent_activity_education_level', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('independent_activity_id');
            $table->foreign('independent_activity_id')->references('id')->on('independent_activities');
            $table->unsignedBigInteger('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels');
            $table->unique(['independent_activity_id', 'education_level_id']);
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
        Schema::dropIfExists('independent_activity_education_level');
    }
}
