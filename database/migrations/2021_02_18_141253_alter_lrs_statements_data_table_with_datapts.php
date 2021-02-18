<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLrsStatementsDataTableWithDatapts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lrs_statements_data', function (Blueprint $table) {
            $table->string('glass_alternate_course_id')->nullable()->index()->comment('Alternate link id of a GC course.');
            $table->string('glass_enrollment_code')->nullable()->index();
            $table->text('course_name')->nullable();
            $table->string('chapter_name', 100)->nullable()->comment('Name of the chapter where the activity is');
            $table->integer('chapter_index')->nullable()->comment('Chapter or slide index where the activity is');
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
            $table->dropColumn('glass_alternate_course_id');
            $table->dropColumn('glass_enrollment_code');
            $table->dropColumn('course_name');
            $table->dropColumn('chapter_name');
            $table->dropColumn('chapter_index');
        });
    }
}
