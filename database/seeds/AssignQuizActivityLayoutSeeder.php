<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AssignQuizActivityLayoutSeeder extends Seeder
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

        $quizImg = 'Id6BxSkmuVvZBOHNl9Wd5WUfITh4qFq2DEMO6bOJ.png';

        $organizations = DB::table('organizations')->pluck('id');

        foreach ($organizations as $key => $organization) {

            if (!File::exists($localURL . $quizImg)) {
                $this->copyImage($quizImg);
            }

            $activityLayouts = '';
            $activityLayouts = [
                [
                    'organization_id' => $organization,
                    'title' => 'Quiz',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.QuestionSet 1.17',
                    'order' => 0,
                    'image' => $storageURL . $quizImg,
                ]
            ];

            DB::table('activity_layouts')->insertOrIgnore($activityLayouts);
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
