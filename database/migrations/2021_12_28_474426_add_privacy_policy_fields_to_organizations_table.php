<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivacyPolicyFieldsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->enum('tos_type', ['Parent', 'URL', 'Content'])->nullable();
            $table->string('tos_url')->nullable();
            $table->text('tos_content')->nullable();
            $table->enum('privacy_policy_type', ['Parent', 'URL', 'Content'])->nullable();
            $table->string('privacy_policy_url')->nullable();
            $table->text('privacy_policy_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'tos_type',
                'tos_url',
                'tos_content',
                'privacy_policy_type',
                'privacy_policy_url',
                'privacy_policy_content'
            ]);
        });
    }
}
