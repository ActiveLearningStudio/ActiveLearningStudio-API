<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DefaultActivityLayoutSeeder extends Seeder
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

        $InteractiveVideoImg = 'mfzc7dF8GW4NToalg6X4WRt4maHZSu5r8lXPjBbj.png';
        $columnLayoutImg = 'EdE8yAybW0I4IlU8qpEZqrkIdlaou3CDcBAj1M4D.png';
        $interactiveBookImg = 'CeOAsd4QYEvpgoQpfjrVicxthAP6lM2G7LaTRFyd.png';
        $coursePresentationImg = 'rF2Vdw0bT3T7Fx85FZ7pvvZgzr0ka6DLKFLkVnVT.png';
        $questionnaireImg = 'mtBeC6w0OY8JM4cgPetzBafE6iUPry3omWrsPS6k.png';

        $organizations = DB::table('organizations')->pluck('id');
        $activityLayouts = '';

        foreach ($organizations as $key => $organization) {

            if (!File::exists($localURL . $InteractiveVideoImg)) {
                $this->copyImage($InteractiveVideoImg);
            }

            if (!File::exists($localURL . $columnLayoutImg)) {
                $this->copyImage($columnLayoutImg);
            }

            if (!File::exists($localURL . $interactiveBookImg)) {
                $this->copyImage($interactiveBookImg);
            }

            if (!File::exists($localURL . $coursePresentationImg)) {
                $this->copyImage($coursePresentationImg);
            }

            if (!File::exists($localURL . $questionnaireImg)) {
                $this->copyImage($questionnaireImg);
            }

            $activityLayouts = [
                [
                    'organization_id' => $organization,
                    'title' => 'Interactive Video',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.InteractiveVideo 1.22',
                    'order' => 0,
                    'image' => $storageURL . $InteractiveVideoImg,
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Column Layout',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.Column 1.13',
                    'order' => 0,
                    'image' => $storageURL . $InteractiveVideoImg,
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Interactive Book',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.InteractiveBook 1.3',
                    'order' => 0,
                    'image' => $storageURL . $InteractiveVideoImg,
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Course Presentation',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.CoursePresentation 1.22',
                    'order' => 0,
                    'image' => $storageURL . $InteractiveVideoImg,
                ],
                [
                    'organization_id' => $organization,
                    'title' => 'Questionnaire',
                    'description' => '',
                    'type' => 'h5p',
                    'h5pLib' => 'H5P.Questionnaire 1.3',
                    'order' => 0,
                    'image' => $storageURL . $InteractiveVideoImg,
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
