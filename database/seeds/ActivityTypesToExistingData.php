<?php

use Illuminate\Database\Seeder;

class ActivityTypesToExistingData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activityTypes = DB::table('activities')->where('type', 'h5p_standalone')->update(['activity_type' => 'STANDALONE']);
    }
}
