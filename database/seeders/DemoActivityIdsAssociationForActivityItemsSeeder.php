<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoActivityIdsAssociationForActivityItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layoutVideoIDs = [];
        $itemsNameList = [];

        if (file_exists(public_path('sample/activity-items-video-links.csv'))) {
            $file = fopen(public_path('sample/activity-items-video-links.csv'), 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($line[0]!== '' && $line[2]!== '') {
                    $layoutVideoIDs[$line[0]] = $line[2];
                    $itemsNameList[] = $line[0];
                }
            }
            fclose($file);
        } else {
            exit();
        }

        $sampleProjectName = config('constants.curriki-sample-project');
        $demoUserEmail = config('constants.curriki-demo-email');
        $organizationId = DB::table('organizations')->where('domain', 'currikistudio')->value('id');

        $activityItems = [];

        $demoActivities = DB::table('activities as a')
                            ->select(
                                'a.id as demo_activity_id',
                                'a.title as activity_title'
                        )
                        ->join('playlists as p', 'p.id', '=', 'a.playlist_id')
                        ->join('projects as proj', 'proj.id', '=', 'p.project_id')
                        ->join('user_project as up', 'up.project_id', '=', 'proj.id')
                        ->join('users as u', 'u.id', '=', 'up.user_id')
                        ->where('u.email', $demoUserEmail)
                        ->where('proj.organization_id', $organizationId)
                        ->where('proj.name', $sampleProjectName)
                        ->get();

        foreach ($demoActivities as $activity) {

            if (in_array($activity->activity_title, $itemsNameList)) {
                $activityItems[$activity->activity_title] = $activity->demo_activity_id;
            } else if (strtolower($activity->activity_title) === 'coming soon!') {
                $activityItems['comingSoon'] = $activity->demo_activity_id;
            }
        }

        $allActivityItems = DB::table('activity_items')->select('id', 'title')->get();
        $currentDate = now();
        $comingSoonID = (isset($activityItems['comingSoon'])) ? $activityItems['comingSoon'] : 0;
        
        foreach ($allActivityItems as $activityItem) {
            
            $demoActivityID = $comingSoonID;
            $demoVideoID = 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs';

            if (isset($activityItems[$activityItem->title])) {
                $demoActivityID = $activityItems[$activityItem->title];
            }
            if (isset($layoutVideoIDs[$activityItem->title])) {
                $demoVideoID = $layoutVideoIDs[$activityItem->title];
            }
            
            DB::table('activity_items')
                ->where('id', $activityItem->id)
                ->update([
                    'demo_activity_id' => $demoActivityID,
                    'demo_video_id' => $demoVideoID,
                    'updated_at' => $currentDate,
                ]);
        }
    }
}
