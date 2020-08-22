<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateH5pCountersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('h5p_counters', function(Blueprint $table)
		{
			$table->string('type', 63);
			$table->string('library_name', 127);
			$table->string('library_version', 31);
			$table->integer('num')->unsigned();
			$table->primary(['type','library_name','library_version'], 'fk_primary');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('h5p_counters');
	}

}
