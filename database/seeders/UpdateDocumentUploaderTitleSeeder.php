<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDocumentUploaderTitleSeeder extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pDocumentUploadParams = ['name' => "H5P.DocumentsUpload", "major_version" => 1, "minor_version" => 0];
        $h5pePubColumnLib = DB::table('h5p_libraries')->where($h5pDocumentUploadParams)->first();

        if (!empty($h5pePubColumnLib)) {

            $title = 'Document Upload';
            DB::table('h5p_libraries')->where($h5pDocumentUploadParams)->update([
                'title' => $title
            ]);
        }

    }
}
