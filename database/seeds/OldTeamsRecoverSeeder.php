<?php

use Illuminate\Database\Seeder;

class OldTeamsRecoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTeams = DB::table('user_team')->select('user_id', 'team_id', 'role')->get();

        foreach ($userTeams as $userTeam) {
            $roleTypeId = '';
            if ($userTeam->role === 'owner') {
                $roleTypeId = 1;
            }
            else if ($userTeam->role === 'collaborator') {
                $roleTypeId = 2;
            }

            DB::table('team_user_roles')->insertOrIgnore([
                'team_id' => $userTeam->team_id,
                'user_id' => $userTeam->user_id,
                'team_role_type_id' => $roleTypeId,
                'created_at' => now(),
            ]);

        }

        $teamProjects = DB::table('team_project')->select('project_id', 'team_id')->get();

        foreach ($teamProjects as $teamProject) {
                DB::table('projects')
                ->where('id', $teamProject->project_id)
                ->whereNull('team_id')
                ->update(['team_id' => $teamProject->team_id]);
        }
    }
}
