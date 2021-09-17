<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableH5pContentsUserDataGoAddSubmissionidPk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('h5p_contents_user_data_go', function (Blueprint $table) {
            $table->primary('submission_id');
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
            $table->dropPrimary('submission_id');
    
        });
    }
}
