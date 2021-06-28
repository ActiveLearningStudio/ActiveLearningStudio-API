<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLrsStatementsDataAddAttemptSubmissionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lrs_statements_data', function (Blueprint $table) {
            $table->string('submission_id')->nullable();
            $table->string('attempt_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lrs_statements_data', function (Blueprint $table) {
            $table->dropColumn('submission_id');
            $table->dropColumn('attempt_id');
        });
    }
}
