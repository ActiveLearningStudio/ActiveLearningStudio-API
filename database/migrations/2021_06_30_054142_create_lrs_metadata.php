<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLrsMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lrs_metadata')) {
            Schema::create(
                'lrs_metadata',
                function (Blueprint $table) {
                    $table->string('activity_id', 50);
                    $table->string('chapter_name', 150);
                    $table->integer('page_order');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lrs_metadata');
    }
}
