<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_request_id')->nullable();
            $table->foreign('export_request_id')->references('id')->on('export_requests');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('export_request_items');
            $table->unsignedBigInteger('item_id');
            $table->enum('item_type', ['USER', 'PROJECT', 'INDEPENDENT-ACTIVITY'])->default("USER");
            $table->enum('item_status', ['PENDING', 'IN-PROGRESS', 'FAILED', 'COMPLETED'])->nullable();
            $table->string('exported_file_path')->nullable();
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
        Schema::dropIfExists('export_request_items');
    }
};
