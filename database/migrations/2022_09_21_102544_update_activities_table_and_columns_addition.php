<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateActivitiesTableAndColumnsAddition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('organization_visibility_type_id')->nullable()->constrained();
            $table->unsignedBigInteger('cloned_from')->nullable()->default(null);
            $table->unsignedBigInteger('clone_ctr')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1); // 1 is for draft
            $table->tinyInteger('indexing')->nullable()->default(null); // null - indexing is not requested
            $table->unsignedBigInteger('original_user')->nullable()->default(null);
            $table->enum('activity_type', ['ACTIVITY', 'INDEPENDENT', 'STANDALONE'])->default("ACTIVITY");
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
            //
        });
    }
}
