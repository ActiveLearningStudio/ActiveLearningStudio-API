<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgANDpublisherToLrsStatementsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lrs_statements_data', function (Blueprint $table) {
            $table->unsignedBigInteger('activity_org_id')->nullable()->after('playlist_id');
            $table->unsignedBigInteger('publisher_id')->nullable()->after('playlist_id');
            $table->unsignedBigInteger('publisher_org_id')->nullable()->after('playlist_id');
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
            $table->dropColumn(['activity_org_id', 'publisher_id', 'publisher_org_id']);
        });
    }
}
