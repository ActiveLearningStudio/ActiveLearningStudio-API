<?php

use Illuminate\Database\Seeder;

class UpdateActivityItemsOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_items')->update(['order' => 100]);

    }
}
