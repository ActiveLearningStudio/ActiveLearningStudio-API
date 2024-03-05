<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class H5PAddEPubColumnLibrarySeederV2 extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pePubColumnLibParams = ['name' => "H5P.EPubColumn", "major_version" => 1, "minor_version" => 1];
        $h5pePubColumnLib = DB::table('h5p_libraries')->where($h5pePubColumnLibParams)->first();

      if (empty($h5pePubColumnLib)) {
          DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.EPubColumn',
                          'title' => 'C2E Column Layout V2',
                          'major_version' => 1,
                          'minor_version' => 1,
                          'patch_version' => 0,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'scripts/h5p-epub-column-layout.js',
                          'preloaded_css' => 'styles/h5p-epub-column-layout.css,styles/custom-epub-column-layout.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);

          $localURL = public_path('storage/activity-items/');
          $storageURL = '/storage/activity-items/';

          $columnLayoutImg = 'EdE8yAybW0I4IlU8qpEZqrkIdlaou3CDcBAj1M4D.png';

          $organizations = DB::table('organizations')->pluck('id');
          $currentDate = now();

          $this->insertActivityLayout($localURL, $columnLayoutImg, $organizations, $storageURL, $currentDate);

          $this->insertActivityItem($localURL, $columnLayoutImg, $organizations, $storageURL, $currentDate);

      }

    }


    private function getSemantics() {
        return '[
  {
    "name": "content",
    "label": "List of Column Content",
    "importance": "high",
    "type": "list",
    "min": 1,
    "entity": "content",
    "field": {
      "name": "content",
      "type": "group",
      "fields": [
        {
          "name": "content",
          "type": "library",
          "importance": "high",
          "label": "Content",
          "options": [
            "H5P.BrightcoveInteractiveVideo 1.2",
            "H5P.QuestionSet 1.20",
            "H5P.Questionnaire 1.2",
            "H5P.EPubDocument 1.0",
            "H5P.Image 1.1",
            "H5P.IFrameEmbed 1.0"
          ]
        },
        {
          "name": "useSeparator",
          "type": "select",
          "importance": "low",
          "label": "Separate content with a horizontal ruler",
          "default": "auto",
          "options": [
            {
              "value": "auto",
              "label": "Automatic (default)"
            },
            {
              "value": "disabled",
              "label": "Never use ruler above"
            },
            {
              "value": "enabled",
              "label": "Always use ruler above"
            }
          ]
        }
      ]
    }
  }
]';
    }


    /**
     * @param $localURL
     * @param $columnLayoutImg
     * @param $organizations
     * @param $storageURL
     * @param $currentDate
     */
    private function insertActivityItem($localURL, $columnLayoutImg, $organizations, $storageURL, $currentDate)
    {
        $itemsArray = [
            'EPubColumn' => [
                'Authors can create a book with large amounts of interactive content. The activities include ePub Document, Interactive Video, Questions, Course Presentations, and more.',
                'Multimedia',
                'H5P.EPubColumn 1.1',
                '0',
                '',
                $columnLayoutImg,

            ]
        ];

        foreach ($organizations as $key => $organization) {

            $activityItems = [];
            $activityTypes = DB::table('activity_types')->whereOrganizationId($organization)->pluck('id', 'title');

            foreach ($itemsArray as $itemKey => $itemRow) {

                if (!isset($activityTypes[$itemRow[1]])) {
                    continue;
                }

                if (!File::exists($localURL . $itemRow[5])) {
                    $this->copyImage($itemRow[5]);
                }

                $activityItemInsert = [
                    'title' => 'C2E Column Layout V2',
                    'image' => $storageURL . $itemRow[5],
                    'description' => $itemRow[0],
                    'activity_type_id' => $activityTypes[$itemRow[1]],
                    'h5pLib' => $itemRow[2],
                    'demo_activity_id' => $itemRow[3],
                    'demo_video_id' => $itemRow[4],
                    'type' => 'h5p',
                    'created_at' => $currentDate,
                    'deleted_at' => null,
                    'organization_id' => $organization,
                ];

                $activityItems[] = $activityItemInsert;
            }

            // Using updateOrInsert() is the recommended way.
            foreach ($activityItems as $activityItem) {
                $params = ['title' => $activityItem['title'], 'organization_id' => $organization];
                DB::table('activity_items')->updateOrInsert($params, $activityItem);
            }
        }
    }


    /**
     * @param $localURL
     * @param $branchingScenarioImg
     * @param $organizations
     * @param $storageURL
     * @param $currentDate
     */
    public function insertActivityLayout($localURL, $columnLayoutImg, $organizations, $storageURL, $currentDate)
    {
        if (!File::exists($localURL . $columnLayoutImg)) {
            $this->copyImage($columnLayoutImg);
        }

        foreach ($organizations as $key => $organization) {
            $activityLayouts = [
                [
                    'organization_id' => $organization,
                    'title' => 'C2E Column Layout V2',
                    'description' => 'Authors can create a book with large amounts of interactive content. The activities include ePub Document, Interactive Video, Questions, Course Presentations, and more.',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.EPubColumn 1.1',
                    'order' => 0,
                    'image' => $storageURL . $columnLayoutImg,
                    'created_at' => $currentDate,
                    'deleted_at' => null
                ]
            ];
            // Using updateOrInsert() is the recommended way.
            foreach ($activityLayouts as $activityLayout) {
                $params = ['title' => $activityLayout['title'], 'organization_id' => $organization];
                DB::table('activity_layouts')->updateOrInsert($params, $activityLayout);
            }
        }
    }

    /**
     * Copy image from another server.
     *
     * @return void
     */
    public function copyImage($image)
    {
        $liveURL = 'https://studio.curriki.org/api/storage/activity-items/';
        $localURL = public_path('storage/activity-items/');

        $liveImageSrc = $liveURL . $image;
        $destination = $localURL . $image;

        if (@file_get_contents($liveURL . $image)) {
            copy($liveImageSrc, $destination);
        }
    }

}
