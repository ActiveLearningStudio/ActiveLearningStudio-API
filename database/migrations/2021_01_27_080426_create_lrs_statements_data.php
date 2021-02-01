<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLrsStatementsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lrs_statements_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statement_id')->index();
            $table->uuid('statement_uuid')->index();
            $table->string('actor_id')->index();
            $table->string('actor_homepage', 150);
            $table->unsignedBigInteger('class_id')->nullable()->index(); // or course id for canvas
            $table->unsignedBigInteger('project_id')->index(); // from curriki
            $table->string('project_name'); // from curriki
            $table->unsignedBigInteger('playlist_id')->index(); // from curriki
            $table->string('playlist_name'); // from curriki
            $table->string('assignment_id', 30)->index(); // gc assignment id, or canvas assignment id
            $table->string('assignment_name'); // could be activity name, and activity id, for now.
            $table->string('page', 100)->nullable();
            $table->text('question')->nullable();
            $table->text('options')->nullable();
            $table->text('answer')->nullable();
            $table->boolean('page_completed')->nullable()->index();
            $table->boolean('assignment_submitted')->nullable()->index();
            $table->string('verb', 30)->index();
            $table->string('platform', 45)->nullable()->index();
            $table->string('object_id')->index(); // target id (iri)
            $table->string('object_name', 150)->index(); // target name
            $table->string('activity_category', 45)->nullable();
            $table->float('duration', 11, 2)->nullable(); // duration of the result
            $table->float('score_scaled', 1, 3)->nullable(); //scaled
            $table->float('score_min', 11, 2)->nullable(); 
            $table->float('score_max', 11, 2)->nullable();
            $table->float('score_raw', 11, 2)->nullable();
            $table->timestamp('datetime'); //interaction datetime.
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
        Schema::dropIfExists('lrs_statements_data');
    }
}
