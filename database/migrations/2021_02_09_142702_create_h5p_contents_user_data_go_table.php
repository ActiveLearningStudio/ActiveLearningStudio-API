<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pContentsUserDataGoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_contents_user_data_go', function (Blueprint $table) {
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('h5p_contents');
            $table->text('user_id');
            $table->unsignedInteger('sub_content_id');
            $table->string('data_id', 127);
            $table->text('data');
            $table->boolean('preload')->default(0);
            $table->boolean('invalidate')->default(0);
            $table->timestamp('updated_at');
            $table->string('go_integration', 127);
            $table->primary(['content_id', 'user_id', 'sub_content_id', 'data_id'], 'fk_primary');
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
        Schema::dropIfExists('h5p_contents_user_data_go');
    }
}
