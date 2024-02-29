<?php


use Database\Seeders\UpdateDocumentUploaderTitleSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateDocumentUploaderTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateDocumentUploaderTitleSeeder::class,
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
