<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateH5pContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_contents', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->string('title');
			$table->integer('library_id')->unsigned();
			$table->longText('parameters');
			$table->longText('filtered');
			$table->string('slug', 127);
			$table->string('embed_type', 127);
			$table->integer('disable')->unsigned()->default(0);
			$table->string('content_type', 127)->nullable();
			$table->longText('authors')->nullable();
			$table->string('source', 2083)->nullable();
			$table->integer('year_from')->unsigned()->nullable();
			$table->integer('year_to')->unsigned()->nullable();
			$table->string('license', 32)->nullable();
			$table->string('license_version', 10)->nullable();
			$table->longText('license_extras')->nullable();
			$table->longText('author_comments')->nullable();
			$table->longText('changes')->nullable();
			$table->string('default_language', 32)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_contents');
	}

}
