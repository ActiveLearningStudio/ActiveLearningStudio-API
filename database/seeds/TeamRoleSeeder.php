<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamRoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('team_role_types')->insertOrIgnore([
            'name' => 'admin',
            'display_name' => 'Team Administrator',
            'created_at' => now()
        ]);
        DB::table('team_role_types')->insertOrIgnore([
            'name' => 'contributor',
            'display_name' => 'Team Contributor',
            'created_at' => now()
        ]);
        DB::table('team_role_types')->insertOrIgnore([
            'name' => 'member',
            'display_name' => 'Team Read-Only Member',
            'created_at' => now()
        ]);
    }
}
