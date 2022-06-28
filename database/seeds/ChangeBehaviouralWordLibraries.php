<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChangeBehaviouralWordLibraries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //H5P.DragText-1.8
        $h5pDragTextLibParams = ['name' => "H5P.DragText", "major_version" =>1, "minor_version" => 8];
        $h5pDragTextLib = DB::table('h5p_libraries')->where($h5pDragTextLibParams)->first();
        $content = file_get_contents('https://raw.githubusercontent.com/ActiveLearningStudio/H5P.Distribution/develop/libraries/H5P.DragText-1.8/semantics.json');
       
        if ($h5pDragTextLib) {
            DB::table('h5p_libraries')->where($h5pDragTextLibParams)->update([
                'semantics' => $content
            ]);
        }

        //H5P.DragQuestion-1.14
        $h5pDragQuestionLibParams = ['name' => "H5P.DragQuestion", "major_version" =>1, "minor_version" => 14];
        $h5pDragQuestionLib = DB::table('h5p_libraries')->where($h5pDragQuestionLibParams)->first();
        $content = file_get_contents('https://raw.githubusercontent.com/ActiveLearningStudio/H5P.Distribution/develop/libraries/H5P.DragQuestion-1.14/semantics.json');

        if ($h5pDragQuestionLib) {
            DB::table('h5p_libraries')->where($h5pDragQuestionLibParams)->update([
                'semantics' => $content
            ]);
        }

        //H5P.AdvancedBlanks-1.0
        $h5pAdvancedBlanksLibParams = ['name' => "H5P.AdvancedBlanks", "major_version" =>1, "minor_version" => 0];
        $h5pAdvancedBlanksLib = DB::table('h5p_libraries')->where($h5pAdvancedBlanksLibParams)->first();
        $content = file_get_contents('https://raw.githubusercontent.com/ActiveLearningStudio/H5P.Distribution/develop/libraries/H5P.AdvancedBlanks-1.0/semantics.json');
       
        if ($h5pAdvancedBlanksLib) {
            DB::table('h5p_libraries')->where($h5pAdvancedBlanksLibParams)->update([
                'semantics' => $content
            ]);
        }
    }

}
