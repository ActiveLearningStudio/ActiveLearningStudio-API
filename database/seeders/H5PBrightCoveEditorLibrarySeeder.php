<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PBrightCoveEditorLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $h5pImmersiveReaderLibParams = ['name' => "H5PEditor.BrightcoveInteractiveVideo"];
      $h5pImmersiveReaderLib = DB::table('h5p_libraries')->where($h5pImmersiveReaderLibParams)->first();

      if (empty($h5pImmersiveReaderLib)) {
          
          DB::table('h5p_libraries')->insert([
                          'name' => 'H5PEditor.BrightcoveInteractiveVideo',
                          'title' => 'H5PEditor.BrightcoveInteractiveVideo',
                          'major_version' => 1,
                          'minor_version' => 0,
                          'patch_version' => 0,
                          'embed_types' => ' ',
                          'runnable' => 0,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'Scripts/image-radio-button-group.js, Scripts/interactive-video-editor.js, Scripts/guided-tours.js, Scripts/require-completion.js',
                          'preloaded_css' => 'styles/image-radio-button-group.css, styles/interactive-video-editor.css, styles/require-completion.css',
                          'semantics' => ' ',
                          'tutorial_url' => ' ',
                          'has_icon' => 0
          ]);
      }
    
    }


}
