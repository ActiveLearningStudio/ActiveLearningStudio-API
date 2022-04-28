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
            $table->foreignId('independent_activity_id')->constrained();
            $table->foreignId('education_level_id')->constrained();
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
