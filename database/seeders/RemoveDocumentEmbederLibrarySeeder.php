<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveDocumentEmbederLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return DB::transaction(function () {
            $library = DB::table('h5p_libraries')
            ->where('name', 'ilike', "H5P.DocumentsUpload")
            ->first();

            if ($library) {
                $deleteLibraryofLibrary = DB::table('h5p_libraries_libraries')->where('library_id', $library->id)->delete();
                $deleteLibrary = DB::table('h5p_libraries')->where('name', 'ilike', "H5P.DocumentsUpload")->delete();
            }
        });
    }
}
