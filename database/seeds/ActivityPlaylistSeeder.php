<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ActivityPlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $select = DB::table('activities')->select('id', 'playlist_id', 'order', 'created_at', 'updated_at');
            DB::table('activity_playlist')->insertUsing(['activity_id', 'playlist_id', 'order', 'created_at', 'updated_at'], $select);   
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
