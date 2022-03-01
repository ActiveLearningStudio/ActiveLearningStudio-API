<?php

use Illuminate\Database\Seeder;

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

        $activities = DB::table('activities')->select('id', 'education_level_id', 'organization_id')->whereNotNull('education_level_id')->get();
        $activitiesSubjectInsertArray = [];

        foreach ($activities as $activity) {
            $activitiesSubjectInsertArray[] = [
                'activity_id' => $activity->id,
                'education_level_id' => $educationLevelsList[$activity->education_level_id][$activity->organization_id],
                'created_at' => now(),
            ];
        }

        DB::table('activity_education_level')->insertOrIgnore($activitiesSubjectInsertArray);
    }
}
