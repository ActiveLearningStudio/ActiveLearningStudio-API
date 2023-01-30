<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignMissingDefaultMediaSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageSources = DB::table('media_sources')
                           ->whereMediaType('Image')
                           ->whereIn('name', ['My device', 'Pexels'])
                           ->pluck('id')->toArray();

        $videoSources = DB::table('media_sources')
                          ->whereMediaType('Video')
                          ->whereNotIn('name', ['Safari Montage', 'BrightCove', 'Komodo'])
                          ->pluck('id')->toArray();

        // get only parent Orgs and assign missing default media sources    
        $organizations = Organization::whereNull('parent_id')->get();

        foreach ($organizations as $organization) {
            if ($organization->mediaSources()->count() === 0) {
                $organization->mediaSources()->attach(array_merge($videoSources, $imageSources));
            }
        }

        // get only child Orgs and inherit default media sources from parent    
        $organizations = Organization::whereNotNull('parent_id')->get();

        foreach ($organizations as $organization) {
            if ($organization->mediaSources()->count() === 0) {
                $parentMediaSources = $organization->parent->mediaSources->pluck('id')->toArray();
                $organization->mediaSources()->attach($parentMediaSources);
            }
        }

    }

}
