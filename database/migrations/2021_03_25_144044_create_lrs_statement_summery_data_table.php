<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLrsStatementSummeryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lrs_statement_summery_data', function (Blueprint $table) {
            $table->id();
            $table->string('class_id')->index();
            $table->string('assignment_name');
            $table->string('user_id')->index();
            $table->string('page_name');
            $table->boolean('is_page_accessed')->nullable()->index();
            $table->boolean('is_event_interacted')->nullable()->index();
            $table->integer('interacted_count');
            $table->integer('total_interactions');
            $table->integer('score');
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
        Schema::dropIfExists('lrs_statement_summery_data');
    }
}
