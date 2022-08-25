<?php

use Illuminate\Database\Seeder;

class CoursePresentationPreloadedPdfChangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pDragTextLibParams = ['name' => "H5PEditor.CoursePresentation", "major_version" =>1, "minor_version" => 24];
        $h5pDragTextLib = DB::table('h5p_libraries')->where($h5pDragTextLibParams)->first();
        
        if ($h5pDragTextLib) {
            DB::table('h5p_libraries')->where($h5pDragTextLibParams)->update([
                'preloaded_js' => 'scripts/pdfjs/build/pdf.js, scripts/disposable-boolean.js, scripts/cp-editor.js, scripts/slide-selector.js, scripts/bg-selector.js   ',
            ]);
        }
    }
}
