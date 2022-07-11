<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMediaSourceIdColumnToLTIToolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lti_tool_settings')
        ->where('tool_type', 'kaltura')
        ->update(
        	[
        		'media_source_id' => 3
        	]
        );

        DB::table('lti_tool_settings')
        ->where('tool_type', 'safari_montage')
        ->update(
        	[
        		'media_source_id' => 4
        	]
        );

        DB::table('lti_tool_settings')
        ->where('tool_type', 'other')
        ->update(
        	[
        		'media_source_id' => 4
        	]
        );

        DB::table('lti_tool_settings')
        ->where('tool_type', 'youtube')
        ->update(
        	[
        		'media_source_id' => 2
        	]
        );

        DB::table('lti_tool_settings')
        ->where('tool_type', 'vimeo')
        ->update(
        	[
        		'media_source_id' => 6
        	]
        );        
    }
}
