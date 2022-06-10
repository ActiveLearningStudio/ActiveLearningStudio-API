<?php

use Illuminate\Database\Seeder;

class AddJqueryDragQuestionH5PSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.DragQuestion", "major_version" => 1, "minor_version" => 14];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();

      if (!empty($h5pFibLib)) {

            $h5pJQueryUiParams = ['name' => "jQuery.ui", "major_version" => 1, "minor_version" => 10];
            $h5pJqueryUiLib = DB::table('h5p_libraries')->where($h5pJQueryUiParams)->first();
            if (!empty($h5pJqueryUiLib)) {

                $h5pJqueryUiLibId = $h5pJqueryUiLib->id;

                $h5pJQueryUiLibParams = ['library_id' => $h5pFibLib->id,'required_library_id' => $h5pJqueryUiLibId,'dependency_type' => 'preloaded'];
                $h5pJqueryUiLibMap = DB::table('h5p_libraries_libraries')->where($h5pJQueryUiLibParams)->first();
                if(empty($h5pJqueryUiLibMap)) {
                    
                    DB::table('h5p_libraries_libraries')->insert([
                        'library_id' => $h5pFibLib->id,
                        'required_library_id' => $h5pJqueryUiLibId,
                        'dependency_type' => 'preloaded'
                    ]);
                }
               
            }
            
        }

    }
}
