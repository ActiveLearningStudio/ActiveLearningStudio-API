<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrldecodeActivityTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities = DB::table('activities')->get();

        DB::transaction(function () use ($activities) {
            foreach ($activities as $activity) {
                $affected = DB::table('activities')
                  ->where('id', $activity->id)
                  ->update(['title' => html_entity_decode($activity->title)]);
            }
        });
    }
}
