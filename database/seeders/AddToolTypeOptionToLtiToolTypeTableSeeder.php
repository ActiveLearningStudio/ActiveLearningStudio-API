<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddToolTypeOptionToLtiToolTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lti_tool_type')->insertOrIgnore([
            'name' => 'Kaltura',
            'created_at' => now()
        ]);
    }
}
