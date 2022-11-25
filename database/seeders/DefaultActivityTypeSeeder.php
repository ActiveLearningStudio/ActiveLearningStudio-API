<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $localURL = public_path('storage/activity-types/');
        $storageURL = '/storage/activity-types/';

        $audioImg = 'jxojJoikZh9DadzBY32rtBTgYRhgSB54q5Y8km0r.png';
        $informationalImg = 'clORiKEO1cluA67iLnAQsjZvG15oje7AOsegUGnW.png';
        $photoImagesImg = '6YnACZgjEQnkjJhUHF2LIWJAxrfwxR7BdjTxF1jV.png';
        $multimediaImg = 'dWf2vW0ex9i17ojCs6T7atGF9jQFDaUCgzYuaBhj.png';
        $mathematicsImg = 'qOIDYdUrBU9XfF20znNLHMBAPpQS7qWUtVEtlmPr.png';
        $questionsImg = 'gU5vnntOG17Z49Idjv99jlXiDYkLHDuFcTg4Ysiw.png';

        $organizations = DB::table('organizations')->pluck('id');

        foreach ($organizations as $key => $organization) {
            $activity_types = '';

            if (!File::exists($localURL . $audioImg)) {
                $this->copyImage($audioImg);
            }

            if (!File::exists($localURL . $informationalImg)) {
                $this->copyImage($informationalImg);
            }

            if (!File::exists($localURL . $photoImagesImg)) {
                $this->copyImage($photoImagesImg);
            }

            if (!File::exists($localURL . $multimediaImg)) {
                $this->copyImage($multimediaImg);
            }

            if (!File::exists($localURL . $mathematicsImg)) {
                $this->copyImage($mathematicsImg);
            }

            if (!File::exists($localURL . $questionsImg)) {
                $this->copyImage($questionsImg);
            }

            $activity_types = [
                [
                    'title' => 'Audio changed',
                    'order' => 0,
                    'image' => $storageURL . $audioImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Informational',
                    'order' => 0,
                    'image' => $storageURL . $informationalImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Photo / Images',
                    'order' => 0,
                    'image' => $storageURL . $photoImagesImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Multimedia',
                    'order' => 0,
                    'image' => $storageURL . $multimediaImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Questions',
                    'order' => 0,
                    'image' => $storageURL . $questionsImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Mathematics',
                    'order' => 0,
                    'image' => $storageURL . $mathematicsImg,
                    'created_at' => now(),
                    'organization_id' => $organization,
                ]
            ];

            DB::table('activity_types')->insertOrIgnore($activity_types);
        }

    }

    /**
     * Copy image from another server.
     *
     * @return void
     */
    public function copyImage($image)
    {
        $liveURL = 'https://studio.curriki.org/api/storage/activity-types/';
        $localURL = public_path('storage/activity-types/');

        $liveImageSrc = $liveURL . $image;
        $destination = $localURL . $image;

        if (@file_get_contents($liveURL . $image)) {
            copy($liveImageSrc, $destination);
        }
    }
}
