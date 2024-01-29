<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class H5PAddEpubColumnLayoutInActivityLayoutSeeder extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pePubColumnLibParams = ['name' => "H5P.EPubColumn", "major_version" => 1, "minor_version" => 0];
        $h5pePubColumnLib = DB::table('h5p_libraries')->where($h5pePubColumnLibParams)->first();

      if (!empty($h5pePubColumnLib)) {

          $localURL = public_path('storage/activity-items/');
          $storageURL = '/storage/activity-items/';

          $columnLayoutImg = 'EdE8yAybW0I4IlU8qpEZqrkIdlaou3CDcBAj1M4D.png';

          $organizations = DB::table('organizations')->pluck('id');
          $currentDate = now();

          $this->insertActivityLayout($localURL, $columnLayoutImg, $organizations, $storageURL, $currentDate);

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
                    'title' => 'EPub Column Layout',
                    'description' => 'Authors can create a book with large amounts of interactive content. The activities include ePub Document, Interactive Video, Questions, Course Presentations, and more.',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.EPubColumn 1.0',
                    'order' => 5,
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
