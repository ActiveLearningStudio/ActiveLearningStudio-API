<?php

use Illuminate\Database\Seeder;

class ActivitySubjectIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')
            ->where([
                ['subject_id', '=', 'CareerTechnicalEducation']
            ])
            ->update(['subject_id' => 'Career & Technical Education']);

        DB::table('activities')
            ->where([
                ['subject_id', '=', 'ComputerScience']
            ])
            ->update(['subject_id' => 'Computer Science']);

        DB::table('activities')
            ->where([
                ['subject_id', '=', 'LanguageArts']
            ])
            ->update(['subject_id' => 'Language Arts']);

        DB::table('activities')
            ->where([
                ['subject_id', '=', 'SocialStudies']
            ])
            ->update(['subject_id' => 'Social Studies']);
    }
}
