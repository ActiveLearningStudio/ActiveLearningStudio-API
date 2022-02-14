<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activityLayouts = [
            [
                'id' => 1,
                'title' => 'Interactive Video',
                'description' => '',
                'type' => 'h5p',
                'h5pLib' => 'H5P.InteractiveVideo 1.22',
                'order' => 0,
                'image' => '/storage/activity-items/P9V8uDSc4KAbHUhcELtLURyIC56b5TwuA8keURDU.svg',
            ],
            [
                'id' => 2,
                'title' => 'Column Layout',
                'description' => '',
                'type' => 'h5p',
                'h5pLib' => 'H5P.Column 1.13',
                'order' => 0,
                'image' => '/storage/activity-items/7VcwSIMLNVD7PoOP4Gm2xJeMS8Ep0z1Tdlu6gX07.svg',
            ],
            [
                'id' => 3,
                'title' => 'Interactive Book',
                'description' => '',
                'type' => 'h5p',
                'h5pLib' => 'H5P.InteractiveBook 1.3',
                'order' => 0,
                'image' => '/storage/activity-items/TdMTKMbahoJua8t1gFMo2aCTiomBvknQnZix7mYg.svg',
            ],
            [
                'id' => 4,
                'title' => 'Course Presentation',
                'description' => '',
                'type' => 'h5p',
                'h5pLib' => 'H5P.CoursePresentation 1.22',
                'order' => 0,
                'image' => '/storage/activity-items/9o6gGxgLoIdyDI75NnHCBmIlQqgpDz2bK5lJLQjF.svg',
            ],
            [
                'id' => 5,
                'title' => 'Questionnaire',
                'description' => '',
                'type' => 'h5p',
                'h5pLib' => 'H5P.Questionnaire 1.3',
                'order' => 0,
                'image' => '/storage/activity-items/bAZWBNFfRTEMfuTyDqIyiERjli9k5GHNS9ZzK0Es.svg',
            ]
        ];

        // Using updateOrInsert() is the recommended way.
        foreach ($activityLayouts as $layout) {
            DB::table('activity_layouts')->updateOrInsert(['id' => $layout['id']], $layout);
        }
    }
}
