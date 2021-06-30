<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLrsStateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            'lrs_statements_data',
            function (Blueprint $table) {
                $table->string('chapter_name')->default(0)->change();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table(
            'lrs_statements_data',
            function (Blueprint $table) {
                $table->string('chapter_name')->default(null)->change();
            }
        );
    }
}
