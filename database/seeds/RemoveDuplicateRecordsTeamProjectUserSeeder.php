<?php

use Illuminate\Database\Seeder;

class RemoveDuplicateRecordsTeamProjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teamProjectUsers = DB::table('team_project_user')
                        ->select('user_id', 'team_id', 'project_id', DB::raw('COUNT(*)'))
                        ->groupBy('user_id', 'team_id', 'project_id')
                        ->havingRaw('COUNT(*) > 1')
                        ->get();

        return DB::transaction(function () use($teamProjectUsers) {

            foreach ($teamProjectUsers as $teamProjectUser) {
                DB::table('team_project_user')
                ->where('user_id', $teamProjectUser->user_id)
                ->where('project_id', $teamProjectUser->project_id)
                ->where('team_id', $teamProjectUser->team_id)
                ->delete();
            }

            foreach ($teamProjectUsers as $teamProjectUser) {
                DB::table('team_project_user')->insertOrIgnore([
                    'team_id' => $teamProjectUser->team_id,
                    'user_id' => $teamProjectUser->user_id,
                    'project_id' => $teamProjectUser->project_id,
                ]);
            }

        });
    }
}
