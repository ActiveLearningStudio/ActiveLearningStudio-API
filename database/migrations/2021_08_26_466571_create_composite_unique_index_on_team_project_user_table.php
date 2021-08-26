<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositeUniqueIndexOnTeamProjectUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_project_user', function (Blueprint $table) {
            $table->unique(['team_id', 'project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_project_user', function (Blueprint $table) {
            $table->dropUnique(['team_id', 'project_id', 'user_id']);
        });
    }
}
