<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PStarRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $h5pImmersiveReaderLibParams = ['name' => "H5P.StarRating"];
      $h5pImmersiveReaderLib = DB::table('h5p_libraries')->where($h5pImmersiveReaderLibParams)->first();

      if (empty($h5pImmersiveReaderLib)) {
          
          DB::table('h5p_libraries')->insert([
                          'name' => 'H5P.StarRating',
                          'title' => 'H5P.StarRating',
                          'major_version' => 1,
                          'minor_version' => 0,
                          'patch_version' => 0,
                          'embed_types' => ' ',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'scripts/text.js',
                          'preloaded_css' => 'styles/text.css',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 0
          ]);
      }
    
    }


    private function getSemantics() {
      return '[
        {
          "name": "text",
          "type": "text",
          "widget": "html",
          "importance": "high",
          "label": "Star rating description",
          "enterMode": "p",
          "tags": [
            "strong",
            "em",
            "del",
            "a",
            "ul",
            "ol",
            "h2",
            "h3",
            "hr",
            "pre",
            "code"
          ]
        },
        {
          "name": "starcounter",
          "type": "number",
          "default": 5,
          "importance": "high",
          "label": "Total number of icons"
        },
        {
          "name": "icontype",
          "type": "select",
          "label": "Select icon",
          "options": [
            {
              "value": "star",
              "label": "star"
            },
            {
              "value": "circle",
              "label": "circle"
            }
          ],
          "default": "star"
        }
      ]';
    }

}
