<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySubjectAssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjectList = [];
        $subjects = DB::table('subjects')->select('id', 'name', 'organization_id')->get();

        foreach ($subjects as $subject) {
            $subjectList[$subject->name][$subject->organization_id] = $subject->id;
        }

        $activities = DB::table('activities as a')
                         ->select(
                            'a.id',
                            'a.subject_id',
                            'a.organization_id as sav_organization_id',
                            'proj.organization_id'
                          )
                         ->leftJoin('playlists as p', 'p.id', '=', 'a.playlist_id')
                         ->leftJoin('projects as proj', 'proj.id', '=', 'p.project_id')
                         ->whereNotNull('a.subject_id')
                         ->get();

        $activitiesSubjectInsertArray = [];

        foreach ($activities as $activity) {
            if (isset($subjectList[$activity->subject_id][$activity->sav_organization_id])) {
               $subject_id = $subjectList[$activity->subject_id][$activity->sav_organization_id];
            } else {
                if (!isset($subjectList[$activity->subject_id][$activity->organization_id])) {
                    continue;
                }
                $subject_id = $subjectList[$activity->subject_id][$activity->organization_id];
            }

            $activitiesSubjectInsertArray = [
                'activity_id' => $activity->id,
                'subject_id' => $subject_id,
                'created_at' => now(),
            ];

            DB::table('activity_subject')->insertOrIgnore($activitiesSubjectInsertArray);
        }

    }
}
