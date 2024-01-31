<?php


use Database\Seeders\UpdateEpubDocumentLibraryV2Seeder;
use Illuminate\Database\Migrations\Migration;

class UpdateEpubDocumentLibraryV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Artisan::call('db:seed', [
            '--class' => UpdateEpubDocumentLibraryV2Seeder::class,
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
