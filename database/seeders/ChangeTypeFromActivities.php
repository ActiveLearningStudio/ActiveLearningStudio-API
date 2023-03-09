<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChangeTypeFromActivities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('activities')->whereNotIn('type', ["h5p_standalone", "h5p"])->update(['type' => "h5p"]);
    }
}
