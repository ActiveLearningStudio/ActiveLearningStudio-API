<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateH5pLibrariesLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_libraries_languages', function(Blueprint $table)
		{
			$table->integer('library_id')->unsigned();
			$table->string('language_code', 31);
			$table->text('translation', 65535);
			$table->primary(['library_id','language_code'], 'fk_primary');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_libraries_languages');
	}

}
