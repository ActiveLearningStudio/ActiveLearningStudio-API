<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueKeyStatementUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lrs_statements_data', function ($table) {
            $table->dropUnique('lrs_statements_data_statement_uuid_unique');
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
            $table->unique('statement_uuid');
        });
    }
}
