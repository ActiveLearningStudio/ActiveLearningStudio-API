<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultMembershipTypeIdInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Changing to smallint because doctrine crashes when trying to change the tinyint column
            // https://github.com/laravel/framework/issues/8840
            // workaround for this is to convert SMALLINT first, then TINYINT
            $table->smallInteger('membership_type_id')->tinyInteger('membership_type_id')->unsigned()->default(2)->change(); // default to free membership as demo will be deprecated
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('membership_type_id')->tinyInteger('membership_type_id')->unsigned()->default(1)->change();  // default to demo membership type created by seeder
        });
    }
}
