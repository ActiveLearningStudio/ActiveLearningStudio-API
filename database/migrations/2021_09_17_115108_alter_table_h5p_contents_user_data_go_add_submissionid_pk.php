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
            $table->dropPrimary('fk_primary');
            $table->string('submission_id')->default(0)->change();
            $table->primary(['content_id', 'user_id', 'sub_content_id', 'data_id', 'submission_id'], 'fk_primary');
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
            $table->dropPrimary('fk_primary');
            $table->primary(['content_id', 'user_id', 'sub_content_id', 'data_id'], 'fk_primary');
        });
    }
}
