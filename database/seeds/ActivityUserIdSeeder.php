<?php

use Illuminate\Database\Seeder;

class ActivityUserIdSeeder extends Seeder
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
            $playlistIds = DB::table('activities')->pluck('playlist_id');
            $projectIds = DB::table('playlists')->whereIn('id', $playlistIds)->pluck('project_id');
            $userIds = DB::table('user_project')->whereIn('project_id', $projectIds)->pluck('user_id');
            if(count($userIds) > 0) {
                foreach ($userIds as $userId) {
                    DB::table('activities')->whereIn("playlist_id",  $playlistIds)->update(['user_id' => $userId]);
                }
            } 
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
