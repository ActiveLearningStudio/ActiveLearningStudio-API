<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gapi_access_token');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->json('gapi_access_token')->nullable()->default(null);
            //default(new Expression('(JSON_ARRAY())'));
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
            // don't rollback this because it'll throw exception if column has values greater than default (255) varchar
            // either specify the large string length or change type to text
            $table->text('gapi_access_token')->nullable()->default(null)->change();
        });
    }
}
