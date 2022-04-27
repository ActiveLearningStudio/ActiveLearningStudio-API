<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndependentActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('independent_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('content');
            $table->boolean('shared')->default(false);
            $table->integer('order')->nullable()->default(null);
            $table->boolean('is_public')->default(false);
            $table->unsignedBigInteger('h5p_content_id')->nullable();
            $table->foreign('h5p_content_id')->references('id')->on('h5p_contents');
            $table->string('thumb_url')->nullable()->default(null);
            $table->string('subject_id')->nullable()->default(null);
            $table->string('education_level_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('organization_id')->nullable()->default(null);
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('organization_visibility_type_id')->nullable()->default(null);
            $table->foreign('organization_visibility_type_id')->references('id')->on('organization_visibility_types');
            $table->mediumText('description')->nullable();
            $table->string('source_type')->nullable();
            $table->string('source_url')->nullable();
            $table->unsignedBigInteger('cloned_from')->nullable()->default(null);
            $table->unsignedBigInteger('clone_ctr')->default(0);
            $table->tinyInteger('status')->nullable()->default(1); // 1 is for draft
            $table->tinyInteger('indexing')->nullable()->default(null); // null - indexing is not requested
            $table->unsignedBigInteger('original_user')->nullable()->default(null);          
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
        Schema::dropIfExists('independent_activities');
    }
}
