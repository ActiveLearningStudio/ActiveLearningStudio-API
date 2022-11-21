<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiModulePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_module_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('ui_module_id');
            $table->foreign('ui_module_id')->references('id')->on('ui_modules');
            $table->timestamps();
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
        Schema::dropIfExists('ui_module_permissions');
    }
}
