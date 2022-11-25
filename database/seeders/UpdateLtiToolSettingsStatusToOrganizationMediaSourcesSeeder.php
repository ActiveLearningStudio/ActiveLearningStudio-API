<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLtiToolSettingsStatusToOrganizationMediaSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$getOrganizationsIds = DB::table('organizations')->pluck('id');    	
        $getMediaSourcesIds = DB::table('media_sources')
        						->whereIn('name', ['Kaltura', 'Vimeo', 'Komodo'])
        						->where('media_type', 'Video')
        						->pluck('id');
        if ( !empty($getOrganizationsIds) && !empty($getMediaSourcesIds) ) {
        	foreach ($getOrganizationsIds as $orgId) {
        		foreach ($getMediaSourcesIds as $mSId) {
	        		DB::table('organization_media_sources')
			          ->updateOrInsert(
					          		['media_source_id' => $mSId, 'organization_id' => $orgId],
							        ['lti_tool_settings_status' => 1, 'updated_at' => now()]
							    );
		        }	
        	}        	
        }
    }
}
