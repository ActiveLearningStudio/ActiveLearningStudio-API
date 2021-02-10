<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLrsStatementsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lrs_statements_data', function ($table) {
            $table->unique('statement_id');
            $table->unique('statement_uuid');
            $table->string('actor_id')->nullable()->change();
            $table->string('actor_homepage')->nullable()->change();
            $table->unsignedBigInteger('project_id')->nullable()->change(); // from curriki
            $table->string('project_name')->nullable()->change(); // from curriki
            $table->unsignedBigInteger('playlist_id')->nullable()->change(); // from curriki
            $table->string('playlist_name')->nullable()->change(); // from curriki
            $table->string('assignment_id', 30)->nullable()->change(); // gc assignment id, or canvas assignment id
            $table->string('assignment_name')->nullable()->change(); // could be activity name, and activity id, for now.
            $table->string('object_name')->nullable()->change(); // target name
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lrs_statements_data', function ($table) {
            $table->dropUnique('lrs_statements_data_statement_id_unique');
            $table->dropUnique('lrs_statements_data_statement_uuid_unique');
            $table->string('actor_id')->nullable(false)->change();
            $table->string('actor_homepage')->nullable(false)->change();
            $table->unsignedBigInteger('project_id')->nullable(false)->change(); // from curriki
            $table->string('project_name')->nullable(false)->change(); // from curriki
            $table->unsignedBigInteger('playlist_id')->nullable(false)->change(); // from curriki
            $table->string('playlist_name')->nullable(false)->change(); // from curriki
            $table->string('assignment_id', 30)->nullable(false)->change(); // gc assignment id, or canvas assignment id
            $table->string('assignment_name')->nullable(false)->change(); // could be activity name, and activity id, for now.
            $table->string('object_name', 150)->nullable(false)->change(); // target name
        });
    }
}
