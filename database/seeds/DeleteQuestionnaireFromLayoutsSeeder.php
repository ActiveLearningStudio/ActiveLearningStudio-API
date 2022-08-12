<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteQuestionnaireFromLayoutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::delete("DELETE FROM activity_layouts WHERE title = 'Questionnaire' ");
    }
}
