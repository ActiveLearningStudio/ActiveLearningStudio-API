<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PImmersiveReaderEditorSemanticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $h5pImmersiveReaderLibParams = ['name' => "H5PEditor.ImmersiveReader"];
      $h5pImmersiveReaderLib = DB::table('h5p_libraries')->where($h5pImmersiveReaderLibParams)->first();

      if (empty($h5pImmersiveReaderLib)) {
          
          DB::table('h5p_libraries')->insert([
                          'name' => 'H5PEditor.ImmersiveReader',
                          'title' => 'H5PEditor.ImmersiveReader',
                          'major_version' => 1,
                          'minor_version' => 0,
                          'patch_version' => 2,
                          'embed_types' => ' ',
                          'runnable' => 0,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'scripts/widget.js',
                          'semantics' => ' ',
                          'tutorial_url' => ' ',
                          'has_icon' => 0
          ]);
      }
    
    }

}
