<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultActivityLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = DB::table('organizations')->pluck('id');

        foreach ($organizations as $key => $organization) {
            $activityLayouts = '';

            $activityLayouts = [
                [
                    'organization_id' => $organization,
                    'title' => 'Interactive Video',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.InteractiveVideo 1.22',
                    'order' => 0,
                    'image' => '/storage/activity-items/P9V8uDSc4KAbHUhcELtLURyIC56b5TwuA8keURDU.svg',
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Column Layout',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.Column 1.13',
                    'order' => 0,
                    'image' => '/storage/activity-items/7VcwSIMLNVD7PoOP4Gm2xJeMS8Ep0z1Tdlu6gX07.svg',
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Interactive Book',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.InteractiveBook 1.3',
                    'order' => 0,
                    'image' => '/storage/activity-items/TdMTKMbahoJua8t1gFMo2aCTiomBvknQnZix7mYg.svg',
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Course Presentation',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.CoursePresentation 1.22',
                    'order' => 0,
                    'image' => '/storage/activity-items/9o6gGxgLoIdyDI75NnHCBmIlQqgpDz2bK5lJLQjF.svg',
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Questionnaire',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.Questionnaire 1.3',
                    'order' => 0,
                    'image' => '/storage/activity-items/bAZWBNFfRTEMfuTyDqIyiERjli9k5GHNS9ZzK0Es.svg',
                ]
            ];

            DB::table('activity_layouts')->insertOrIgnore($activityLayouts);
        }

        
    }
}
