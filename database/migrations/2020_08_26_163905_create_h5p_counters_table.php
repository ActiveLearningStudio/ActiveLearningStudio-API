<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_counters', function (Blueprint $table) {
            $table->string('type', 63);
            $table->string('library_name', 127);
            $table->string('library_version', 31);
            $table->unsignedInteger('num');
            $table->primary(['type', 'library_name', 'library_version'], 'fk_primary');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('h5p_counters');
    }
}
