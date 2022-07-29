<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddBranchingScenarioActivityLayoutAndItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $localURL = public_path('storage/activity-items/');
        $storageURL = '/storage/activity-items/';

        $branchingScenarioImg = 'b2HYsuQVpbPXXFGUynONxft2U7q1evDCvyvUS8ae.png';

        $organizations = DB::table('organizations')->pluck('id');
        $currentDate = now();

        $this->insertActivityLayout($localURL, $branchingScenarioImg, $organizations, $storageURL, $currentDate);

        $this->insertActivityItem($localURL, $branchingScenarioImg, $organizations, $storageURL, $currentDate);

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

    /**
     * @param $localURL
     * @param $branchingScenarioImg
     * @param $organizations
     * @param $storageURL
     * @param $currentDate
     */
    public function insertActivityLayout($localURL, $branchingScenarioImg, $organizations, $storageURL, $currentDate)
    {
        if (!File::exists($localURL . $branchingScenarioImg)) {
            $this->copyImage($branchingScenarioImg);
        }

        foreach ($organizations as $key => $organization) {
            $activityLayouts = [
                [
                    'organization_id' => $organization,
                    'title' => 'Branching Scenario',
                    'description' => 'Create adaptive scenarios for the learner',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.BranchingScenario 1.7',
                    'order' => 0,
                    'image' => $storageURL . $branchingScenarioImg,
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
     * @param $localURL
     * @param $branchingScenarioImg
     * @param $organizations
     * @param $storageURL
     * @param $currentDate
     */
    private function insertActivityItem($localURL, $branchingScenarioImg, $organizations, $storageURL, $currentDate)
    {
        $itemsArray = [
            'BranchingScenario' => [
                'Create adaptive scenarios for the learner',
                'Multimedia',
                'H5P.BranchingScenario 1.7',
                '0',
                '',
                $branchingScenarioImg,

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
                    'title' => 'Branching Scenario',
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


}
