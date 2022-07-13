<?php

use Illuminate\Database\Seeder;

class H5PAddCPEditorLibrarySeeder extends Seeder
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
        //added en language
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pEditorh5pEditorCoursePresentationParamsLibId,
            'language_code' => 'en',
            'translation' => json_encode(json_decode('{"libraryStrings":{"confirmDeleteSlide":"Are you sure you wish to delete this slide?","sortSlide":"Move slide :dir","backgroundSlide":"Slide background","removeSlide":"Delete slide","cloneSlide":"Clone slide","newSlide":"Add new slide","insertElement":"Click and drag to place :type","newKeyword":"New keyword","save":"Save","confirmRemoveElement":"Are you sure you wish to delete this element?","cancel":"Cancel","done":"Done","remove":"Delete","edit":"Edit","keywordsTip":"Drag in keywords using the two buttons above.","loading":"Loading...","slides":"Slides","element":"Element","resetToDefault":"Reset to default","resetToTemplate":"Reset to template","slideBackground":"Slide background","setImageBackground":"Image background","setColorFillBackground":"Color fill background","activeSurfaceWarning":"Are you sure you want to activate Active Surface Mode? This action cannot be undone.","template":"Template","templateDescription":"Will be applied to all slides not overridden by any \":currentSlide\" settings.","currentSlide":"This slide","currentSlideDescription":"Will be applied to this slide only, and will override any \":template\" settings.","showTitles":"Show titles","alwaysShow":"Always show","autoHide":"Auto hide","ok":"OK","slide":"Slide","opacity":"Opacity","goToSlide":"Go to slide","expandBreadcrumbButtonLabel":"Go back","collapseBreadcrumbButtonLabel":"Close navigation"}}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
    }
}
