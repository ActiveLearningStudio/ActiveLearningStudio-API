<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSemanticOpenEndQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFindWordLibParams = ['name' => "H5P.OpenEndedQuestion", "major_version" => 1, "minor_version" => 0];
        $h5pFindWordLib = DB::table('h5p_libraries')->where($h5pFindWordLibParams)->first();
        if ($h5pFindWordLib) {
            DB::table('h5p_libraries')->where($h5pFindWordLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
          {
            "label": "Question or description",
            "name": "question",
            "type": "text",
            "widget": "html",
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
          },
          {
            "name": "placeholderText",
            "label": "Placeholder text",
            "default": "Start writing...",
            "importance": "low",
            "description": "Text that initially will be shown in the input field. Will be removed automatically when the user starts writing.",
            "type": "text",
            "optional": true
          },
          {
            "name": "inputRows",
            "label": "Input rows",
            "type": "select",
            "importance": "high",
            "description": "Determines the height of the input field.",
            "options": [
              {
                "value": "1",
                "label": "1 line"
              },
              {
                "value": "2",
                "label": "2 lines"
              },
              {
                "value": "3",
                "label": "3 lines"
              },
              {
                "value": "10",
                "label": "10 lines"
              }
            ],
            "default": "1"
          }
        ]';
    }
}
