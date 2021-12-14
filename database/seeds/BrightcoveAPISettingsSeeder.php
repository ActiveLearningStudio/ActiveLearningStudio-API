<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrightcoveAPISettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brightcove_api_settings')->insert([
            'user_id' => 1,
            'organization_id' => 1,
            'account_id' => '6282550302001',
            'account_name' => 'Curriki Brightcove CMS',
            'account_eamil' => 'mike@curriki.org',
            'client_id' => 'ba458ab6-ec97-4a7c-a0da-854427823722',
            'client_secret' => 'ohYIarN4dT3YGP-beI2gB_CX2juT3FeDxXLiVFr8b5tuD1XUhcouecv4FdOOYkCewRF1zCdi6dxM5TQs4DW4zQ',
            'description' => 'Brightcove API Testing.',
            'created_at' => date("Y-m-d")
        ]);
    }
}
