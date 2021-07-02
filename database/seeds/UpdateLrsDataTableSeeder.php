<?php

use Illuminate\Database\Seeder;

class UpdateLrsDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('lrs_statements_data')->where('chapter_name', null)->update(['chapter_name' => 0]);
    }
}
