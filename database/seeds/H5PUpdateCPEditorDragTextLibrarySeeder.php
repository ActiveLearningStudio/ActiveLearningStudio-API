<?php

use Illuminate\Database\Seeder;

class H5PUpdateCPEditorDragTextLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $h5pEditorCoursePresentationParams = ['name' => "H5PEditor.CoursePresentation", "major_version" => 1, "minor_version" => 24];
        $h5pEditorh5pEditorCoursePresentationParamsLib = DB::table('h5p_libraries')->where($h5pEditorCoursePresentationParams)->first();
        $h5pEditorh5pEditorCoursePresentationParamsLibId = $h5pEditorh5pEditorCoursePresentationParamsLib->id;
        
        $h5pDragTextParams = ['name' => "H5P.DragText", "major_version" => 1, "minor_version" => 10];
        $h5pDragTextParamsLib = DB::table('h5p_libraries')->where($h5pDragTextParams)->first();
        $h5pDragTextParamsLibId = $h5pDragTextParamsLib->id;
        
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEditorh5pEditorCoursePresentationParamsLibId,
            'required_library_id' => $h5pDragTextParamsLibId,
            'dependency_type' => 'preloaded'
        ]);
    }
}
