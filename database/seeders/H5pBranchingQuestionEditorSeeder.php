<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5pBranchingQuestionEditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pbQLibParams = ['name' => "H5PEditor.BranchingQuestion", "major_version" => 1, "minor_version" => 0];
        $h5pbQLib = DB::table('h5p_libraries')->where($h5pbQLibParams)->first();
        if(!empty($h5pbQLib)) {
            $h5pbQLibId = $h5pbQLib->id;
            DB::table('h5p_libraries_languages')->insert([
                'library_id' => $h5pbQLibId,
                'language_code' => 'en',
                'translation' => json_encode(json_decode('{"libraryStrings": {"feedbackDescription": "It is recommended to provide feedback that motivates and also provides guidance. Leave all fields empty if you donot want the user to get feedback after choosing this alternative/viewing this content."}}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            ]);
        }
    }
}
