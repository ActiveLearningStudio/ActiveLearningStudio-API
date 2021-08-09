<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGcClassApiData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gclass_api_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('course_id');
            $table->string('name')->nullable();
            $table->string('section')->nullable();
            $table->text('description_heading')->nullable();
            $table->text('description')->nullable();
            $table->string('room')->nullable();
            $table->string('owner_id')->nullable();
            $table->string('enrollment_code')->nullable();
            $table->enum('course_state', ['COURSE_STATE_UNSPECIFIED', 'ACTIVE', 'ARCHIVED', 'PROVISIONED', 'DECLINED', 'SUSPENDED'])
                ->default('COURSE_STATE_UNSPECIFIED');
            $table->string('alternate_link')->nullable();
            $table->string('teacher_group_email')->nullable();
            $table->string('course_group_email')->nullable();
            $table->boolean('guardians_enabled')->nullable();
            $table->string('calendar_id')->nullable();
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
        Schema::dropIfExists('gclass_api_data');
    }
}
