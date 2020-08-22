<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateH5pLibrariesHubCacheTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_libraries_hub_cache', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('machine_name', 127);
			$table->integer('major_version')->unsigned();
			$table->integer('minor_version')->unsigned();
			$table->integer('patch_version')->unsigned();
			$table->integer('h5p_major_version')->unsigned()->nullable();
			$table->integer('h5p_minor_version')->unsigned()->nullable();
			$table->string('title');
			$table->text('summary', 65535);
			$table->text('description', 65535);
			$table->string('icon', 511);
			$table->integer('created_at')->unsigned();
			$table->integer('updated_at')->unsigned();
			$table->integer('is_recommended')->unsigned();
			$table->integer('popularity')->unsigned();
			$table->text('screenshots', 65535)->nullable();
			$table->string('license', 511)->nullable();
			$table->string('example', 511);
			$table->string('tutorial', 511)->nullable();
			$table->text('keywords', 65535)->nullable();
			$table->text('categories', 65535)->nullable();
			$table->string('owner', 511)->nullable();
			$table->index(['machine_name','major_version','minor_version','patch_version'], 'name_version');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_libraries_hub_cache');
	}

}
