<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEamilColumnInBrightcoveApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brightcove_api_settings', function (Blueprint $table) {
            $table->renameColumn('account_eamil', 'account_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brightcove_api_settings', function (Blueprint $table) {
           $table->renameColumn('account_email', 'account_eamil');
        });
    }
}
