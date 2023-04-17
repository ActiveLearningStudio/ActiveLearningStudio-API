<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddLtiToolTypeRecordToLtiToolSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get lti tool type id from lti_tool_type table
        $getLtiToolTypeRow = DB::table('lti_tool_type')->where('name', 'Kaltura')->first();
        $ltiToolTypeId = ( empty($getLtiToolTypeRow) ) ? 1 : $getLtiToolTypeRow->id;
        // Get media source id from media_sources table
        $conditionAttr = ['name' => 'Kaltura', 'media_type' => 'Video'];
        $getMediaRow = DB::table('media_sources')->where($conditionAttr)->first();
        $mediaId = ( empty($getMediaRow) ) ? 3 : $getMediaRow->id;

        DB::table('lti_tool_settings')
        ->where('media_source_id', $mediaId)
        ->update(['lti_tool_type_id' => $ltiToolTypeId]);

    }
}
