<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateActivityItemTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = DB::table('activity_items')->where('title', 'Find The Hotspot')->count();
        if($count === 0) {
            DB::table('activity_items')->where('title', 'Findthe Hotspot')->update(['title' => 'Find The Hotspot']);
        }

        DB::delete("DELETE FROM activity_items WHERE title = 'Geo Gebra3 d' ");
    }
}
