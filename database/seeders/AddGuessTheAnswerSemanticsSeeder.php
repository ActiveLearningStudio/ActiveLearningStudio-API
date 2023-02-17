<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddGuessTheAnswerSemanticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.GuessTheAnswer", "major_version" => 1, "minor_version" => 5];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();
        if (empty($h5pFibLib)) {
            $h5pFibLibId = DB::table('h5p_libraries')->insertGetId([
                            'name' => 'H5P.GuessTheAnswer',
                            'title' => 'Guess the Answer',
                            'major_version' => 1,
                            'minor_version' => 5,
                            'patch_version' => 1,
                            'embed_types' => 'iframe',
                            'runnable' => 1,
                            'restricted' => 0,
                            'fullscreen' => 0,
                            'preloaded_js' => 'guess-the-answer.js',
                            'preloaded_css' => 'guess-the-answer.css',
                            'drop_library_css' => '',
                            'semantics' => $this->getSemantics(),
                            'tutorial_url' => ' ',
                            'has_icon' => 1
            ]);

            // insert dependent libraries
            $this->insertDependentLibraries($h5pFibLibId);

            // insert libraries languages
            $this->insertLibrariesLanguages($h5pFibLibId);
        }
    }
            /**
     * Insert Dependent Libraries
     * @param $h5pFibLibId
     */
    private function insertDependentLibraries($h5pFibLibId)
    {
        //Preloaded Dependencies
        $h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pFontAwesomeLibId,
            'dependency_type' => 'preloaded'
        ]);

    }

    /**
     * Insert Library Language Semantics
     * @param $h5pFibLibId
     */
    private function insertLibrariesLanguages($h5pFibLibId)
    {
        // en.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'en',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Task description","description":"Describe how the user should solve the task."},{"label":"Media","fields":[{"label":"Type","description":"Optional media to display above the question."}]},{"label":"Descriptive solution label","default":"Click to see the answer.","description":"Clickable text area where the solution will be displayed."},{"label":"Solution text","description":"The solution for the picture."}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // af.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'af',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Taak beskrywing","description":"Beskryf hoe die gebruiker die taak moet oplos."},{"label":"Media","fields":[{"label":"Tipe","description":"Opsionele media wat bo die vraag vertoon word."}]},{"label":"Beskrywende antwoordetiket","default":"Klik om die antwoord te sien.","description":"Klikbare teksarea waar die oplossing vertoon sal word."},{"label":"Antwoordteks","description":"Die antwoord vir die prent."}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
       
        // bg.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'bg',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Описание на задачата","description":"Опишете как потребителят трябва да реши задачата."},{"label":"Медия","fields":[{"label":"Тип","description":"Незадължителна медия, която ще се показва над въпроса."}]},{"label":"Описателен етикет за решение","default":"Кликнете, за да видите отговора.","description":"Текстова област с възможност за кликване, където ще се показва решението."},{"label":"Текст на решението","description":"Решение за изображението."}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // bs.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'bs',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Opis zadatka","description":"Opišite kako će korinik riješiti zadatak."},{"label":"Media","fields":[{"label":"Type","description":"Optional media to display above the question."}]},{"label":"Opisna oznaka za rješenje","default":"Kliknite da vitite odgovor","description":"Tekstualno područje za klikanje je ono područje gdje će biti prikazano rješenje."},{"label":"Tekst rješenja","description":"Rješenje za sliku."}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
        // ca.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'ca',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Descripció de la tasca","description":"Descriu com l’usuari ha de resoldre la tasca."},{"label":"Recurs","fields":[{"label":"Tipus","description":"Recursos opcionals per mostrar al damunt de la pregunta."}]},{"label":"Etiqueta per a una solució descriptiva","default":"Feu clic per veure la resposta.","description":"Zona de text en què es pot fer clic i on es mostrarà la solució."},{"label":"Text per a Solució","description":"Solució per a la imatge."}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

    }

    private function getSemantics() {
        return '[
  {
    "label": "Task description",
    "importance": "medium",
    "name": "taskDescription",
    "type": "text",
    "widget": "html",
    "description": "Describe how the user should solve the task.",
    "enterMode": "p",
    "tags": [
      "strong",
      "em",
      "u",
      "a",
      "ul",
      "ol",
      "h2",
      "h3",
      "hr",
      "pre",
      "code"
    ],
    "optional": true
  },
  {
    "name": "media",
    "type": "group",
    "label": "Media",
    "importance": "medium",
    "fields": [
      {
        "name": "type",
        "type": "library",
        "label": "Type",
        "importance": "medium",
        "options": [
          "H5P.Image 1.1",
          "H5P.Video 1.6"
        ],
        "optional": true,
        "description": "Optional media to display above the question."
      }
    ]
  },
  {
    "label": "Descriptive solution label",
    "importance": "low",
    "name": "solutionLabel",
    "type": "text",
    "widget": "textarea",
    "default": "Click to see the answer.",
    "description": "Clickable text area where the solution will be displayed.",
    "optional": true
  },
  {
    "label": "Solution text",
    "importance": "high",
    "name": "solutionText",
    "type": "text",
    "widget": "textarea",
    "description": "The solution for the picture."
  }
]';
    }
}
