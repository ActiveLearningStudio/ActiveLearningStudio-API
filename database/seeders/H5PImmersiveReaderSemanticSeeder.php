<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PImmersiveReaderSemanticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pImmersiveReaderLibParams = ['name' => "H5P.ImmersiveReader", "major_version" => 1, "minor_version" => 0];
        $h5pImmersiveReaderLib = DB::table('h5p_libraries')->where($h5pImmersiveReaderLibParams)->first();
        if ($h5pImmersiveReaderLib) {
            DB::table('h5p_libraries')->where($h5pImmersiveReaderLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
          {
            "label": "Content Title",
            "name": "title",
            "type": "text",
            "default": "Content title",
            "description": "The title for the text to be displayed"
          },
          {
            "label": "Content",
            "name": "immersivecontent",
            "type": "text",
            "widget": "html",
            "description": "The text content",
            "enterMode": "div",
            "tags": [
              "strong",
              "b",
              "i",
              "iframe",
              "figure",
              "figcaption",
              "span",
              "style",
              "img",
              "additional",
              "em",
              "del",
              "a",
              "ul",
              "ol",
              "h1",
              "h2",
              "h3",
              "hr",
              "pre",
              "blockquote",
              "div",
              "head",
              "html",
              "table",
              "colgroup",
              "col",
              "tr",
              "td",
              "thead",
              "tbody",
              "tfoot",
              "font",
              "object",
              "embed",
              "body"
            ],
            "font": {
              "size": true,
              "color": true,
              "background": true,
              "family": true,
              "height": true
            }
          }
        ]
        ';
    }
}
