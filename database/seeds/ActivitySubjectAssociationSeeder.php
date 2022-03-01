<?php

use Illuminate\Database\Seeder;

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

        $activities = DB::table('activities')->select('id', 'subject_id', 'organization_id')->whereNotNull('subject_id')->get();
        $activitiesSubjectInsertArray = [];

        foreach ($activities as $activity) {
            if (isset($subjectList[$activity->subject_id][$activity->organization_id])) {
                $activitiesSubjectInsertArray[] = [
                    'activity_id' => $activity->id,
                    'subject_id' => $subjectList[$activity->subject_id][$activity->organization_id],
                    'created_at' => now(),
                ];
            }
        }

        DB::table('activity_subject')->insertOrIgnore($activitiesSubjectInsertArray);
    }
}
