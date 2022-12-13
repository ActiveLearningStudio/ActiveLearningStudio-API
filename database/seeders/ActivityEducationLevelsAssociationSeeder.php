<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityEducationLevelsAssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $educationLevelsList = [];
        $educationLevels = DB::table('education_levels')->select('id', 'name', 'organization_id')->get();

        foreach ($educationLevels as $educationLevel) {
            $educationLevelsList[$educationLevel->name][$educationLevel->organization_id] = $educationLevel->id;
        }

        $activities = DB::table('activities as a')
                         ->select(
                            'a.id',
                            'a.education_level_id',
                            'a.organization_id as sav_organization_id',
                            'p.title',
                            'proj.organization_id'
                          )
                         ->leftJoin('playlists as p', 'p.id', '=', 'a.playlist_id')
                         ->leftJoin('projects as proj', 'proj.id', '=', 'p.project_id')
                         ->whereNotNull('a.education_level_id')
                         ->get();

        $activitiesSubjectInsertArray = [];

        foreach ($activities as $activity) {
            if (isset($educationLevelsList[$activity->education_level_id][$activity->sav_organization_id])) {
                $education_level_id = $educationLevelsList[$activity->education_level_id][$activity->sav_organization_id];
            } else {
                if (!isset($educationLevelsList[$activity->education_level_id][$activity->organization_id])) {
                    continue;
                }
                $education_level_id = $educationLevelsList[$activity->education_level_id][$activity->organization_id];
            }

            $activitiesSubjectInsertArray = [
                'activity_id' => $activity->id,
                'education_level_id' => $education_level_id,
                'created_at' => now(),
            ];

            DB::table('activity_education_level')->insertOrIgnore($activitiesSubjectInsertArray);
        }

    }
}
