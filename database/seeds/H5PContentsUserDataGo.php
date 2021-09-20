<?php

use Illuminate\Database\Seeder;

class H5PContentsUserDataGo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('h5p_contents_user_data_go')->where('submission_id', null)->update(['submission_id' => 0]);
    }
}
