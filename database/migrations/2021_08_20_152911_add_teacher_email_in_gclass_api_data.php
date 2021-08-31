<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherEmailInGclassApiData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gclass_api_data', function (Blueprint $table) {
            $table->string('gclass_teacher_email')->nullable();
            $table->string('curriki_teacher_email')->nullable();
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
            $table->dropColumn(['gclass_teacher_email']);
            $table->dropColumn(['curriki_teacher_email']);
        });
    }
}
