<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmissionidIntoH5pContentsUserDataGoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('h5p_contents_user_data_go', function (Blueprint $table) {
            $table->string('submission_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('h5p_contents_user_data_go', function (Blueprint $table) {
            $table->dropColumn(['submission_id']);
        });
    }
}
