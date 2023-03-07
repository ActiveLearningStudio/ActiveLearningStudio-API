<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class H5pEditorThreeImageUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pThreeImageLibParams = ['name' => "H5PEditor.ThreeImage", "major_version" => 0, "minor_version" => 5];
        $h5pThreeImageLib = DB::table('h5p_libraries')->where($h5pThreeImageLibParams)->first();

        if (empty($h5pThreeImageLib)) {
          $h5pThreeImageLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5PEditor.ThreeImage',
                          'title' => 'Three Image Editor',
                          'major_version' => 0,
                          'minor_version' => 5,
                          'patch_version' => 2,
                          'embed_types' => '',
                          'runnable' => 0,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'dist/h5p-editor-three-image.js',
                          'preloaded_css' => '',
                          'drop_library_css' => '',
                          'semantics' => '',
                          'tutorial_url' => ' ',
                          'has_icon' => 0
          ]);

            // insert dependent libraries
            $this->insertDependentLibraries($h5pThreeImageLibId);

            // insert libraries languages
            $this->insertLibrariesLanguages($h5pThreeImageLibId);
        }
    }

    /**
     * Insert Dependent Libraries
     * @param $h5pThreeImageLibId
     */
    private function insertDependentLibraries($h5pThreeImageLibId)
    {
       
        $h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pFontAwesomeLibId,
            'dependency_type' => 'preloaded'
        ]);

        $h5pFontIconsParams = ['name' => "H5P.FontIcons", "major_version" => 1, "minor_version" => 0];
        $h5pFontIconsLib = DB::table('h5p_libraries')->where($h5pFontIconsParams)->first();
        $h5pFontIconsLibId = $h5pFontIconsLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pFontIconsLibId,
            'dependency_type' => 'preloaded'
        ]);
    }

    /**
     * Insert Library Language Semantics
     * @param $h5pThreeImageLibId
     */
    private function insertLibrariesLanguages($h5pThreeImageLibId)
    {

        // en.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pThreeImageLibId,
            'language_code' => 'en',
            'translation' => json_encode(json_decode('{"libraryStrings":{"noScenesTitle":"No scenes","noScenesDescription":"Click the \"New scene\" button below to add the first scene","closeSceneSelector":"Close the scene selector","cancel":"Cancel","confirm":"Confirm","remove":"Remove","done":"Done","edit":"Edit","delete":"Delete","or":"or","scene":"Scene","loading":"Loading","newScene":"New scene","currentScene":"Current scene","startingScene":"Starting scene","setCameraStart":"Set starting position","setStartingScene":"Set as the starting scene","chooseScene":"Choose a scene","goToScene":"Go to scene","changeSceneTitle":"Scene type changed","changeSceneBody":"Changing the scene type will cause all interactions to be repositioned randomly. Are you sure you wish to proceed?","createASceneToGoTo":"Create a new scene to go to","createSceneError":"Please create a new scene to proceed","pickAnExistingScene":"Pick an existing scene to go to","selectASceneError":"Please select a scene","setCameraStartTooltip":"Set current camera angle as a starting point for this scene","deleteInteractionTitle":"Deleting interaction","deleteInteractionText":"Are you sure you wish to delete this interaction?","deleteSceneTitle":"Deleting scene","deleteSceneText":"Are you sure you wish to delete this scene?","deleteSceneTextWithObjects":"Deleting this scene will also delete all interactions within the scene and any navigational hotspots pointing to this scene. Are you sure you wish to delete this scene?"}}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
    }
}
