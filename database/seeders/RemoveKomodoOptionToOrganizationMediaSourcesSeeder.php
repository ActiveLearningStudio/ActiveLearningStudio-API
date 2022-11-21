<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveKomodoOptionToOrganizationMediaSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mediaSourcesRow = DB::table('media_sources')
        						->where('name','Komodo')
        						->where('media_type', 'Video')
        						->first();
        if (!empty($mediaSourcesRow)) {
        	DB::table('organization_media_sources')
        		->where('media_source_id', $mediaSourcesRow->id)
        		->delete();
        }
    }
}
