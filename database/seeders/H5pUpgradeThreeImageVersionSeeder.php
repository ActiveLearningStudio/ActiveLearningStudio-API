<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class H5pUpgradeThreeImageVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_items')
            ->where(['title' => 'Virtual Tour (360)', 'h5pLib' => 'H5P.ThreeImage 0.3'])
            ->update([
                'h5pLib' => 'H5P.ThreeImage 0.5',
                'updated_at' => now()
            ]);
    }
}
