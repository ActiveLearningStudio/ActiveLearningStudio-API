<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherOrgToGclassApiDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gclass_api_data', function (Blueprint $table) {
           $table->unsignedBigInteger('curriki_teacher_org')->nullable()->after('curriki_teacher_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gclass_api_data', function (Blueprint $table) {
            $table->dropColumn(['curriki_teacher_org']);
        });
    }
}
