<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultOrganizationMediaSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = DB::table('organizations')->pluck('id');

        $imageSources = DB::table('media_sources')
                           ->whereMediaType('Image')
                           ->whereIn('name', ['My device', 'Pexels'])
                           ->pluck('id');

        $videoSources = DB::table('media_sources')
                          ->whereMediaType('Video')
                          ->where('name', '<>', 'Safari Montage')
                          ->pluck('id');

        $currentDate = now();

        foreach ($organizations as $organization) {
            $organizationMedia = '';

            foreach ($videoSources as $videoSourceId) {

                $organizationMedia = [
                    'media_source_id' => $videoSourceId,
                    'organization_id' => $organization,
                    'created_at' => $currentDate,
                ];

                $organizationMedias[] = $organizationMedia;
            }

            foreach ($imageSources as $imageSourceId) {

                $organizationMedia = [
                    'media_source_id' => $imageSourceId,
                    'organization_id' => $organization,
                    'created_at' => $currentDate,
                ];

                $organizationMedias[] = $organizationMedia;
            }

            DB::table('organization_media_sources')->insertOrIgnore($organizationMedias);
        }

    }

}
