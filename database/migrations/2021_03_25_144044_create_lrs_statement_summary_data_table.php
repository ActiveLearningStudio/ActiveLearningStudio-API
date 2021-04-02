<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLrsStatementSummaryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lrs_statement_summary_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statement_id')->index();
            $table->uuid('statement_uuid')->index();
            $table->string('actor_id')->nullable()->index();
            $table->string('actor_homepage', 150)->nullable();
            $table->string('class_id')->nullable()->index();
            $table->string('assignment_name')->nullable();
            $table->string('page_name')->nullable();
            $table->boolean('is_page_accessed')->index();
            $table->boolean('is_event_interacted')->index();
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
        Schema::dropIfExists('lrs_statement_summary_data');
    }
}
