<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedBigInteger('h5p_content_id')->nullable();
            $table->foreign('h5p_content_id')->references('id')->on('h5p_contents');
            $table->string('thumb_url')->nullable()->default(null);
            $table->string('subject_id')->nullable()->default(null);
            $table->string('education_level_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['h5p_content_id', 'thumb_url', 'subject_id', 'education_level_id']);
        });
    }
}
