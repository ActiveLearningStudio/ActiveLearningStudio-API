<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_types')->insert([
            'name' => 'demo',
            'label' => 'Demo',
            'description' => 'Demo account',
            'total_storage' => 524288000, // 500mb
            'total_bandwidth' => 0,
            'price' => 0
        ]);

        DB::table('membership_types')->insert([
            'name' => 'free',
            'label' => 'Free',
            'description' => 'Free account',
            'total_storage' => 1073741824, // 1gb
            'total_bandwidth' => 0,
            'price' => 0
        ]);

        DB::table('membership_types')->insert([
            'name' => 'basic',
            'label' => 'Basic',
            'description' => 'Basic account',
            'total_storage' => 5368709120, // 5gb
            'total_bandwidth' => 0,
            'price' => 199
        ]);
    }
}
