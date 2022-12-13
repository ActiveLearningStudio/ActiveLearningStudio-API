<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoActivityIdsAssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sampleProjectName = config('constants.curriki-sample-project');
        $demoUserEmail = config('constants.curriki-demo-email');
        $organizationId = DB::table('organizations')->where('domain', 'currikistudio')->value('id');

        $layoutVideoIDs = [
            'Interactive Video' => 'https://www.youtube-nocookie.com/embed/7FnoeS8fxXg',
            'Column Layout' => 'https://www.youtube-nocookie.com/embed/ngXSzWNYzU4',
            'Course Presentation' => 'https://www.youtube-nocookie.com/embed/b1_-JJWKh3w',
            'Interactive Book' => 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
            'Quiz' => 'https://www.youtube-nocookie.com/embed/t0vsfxiq1zk',
            'Coming Soon' => 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
        ];

        $layouts = [];

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
                        ->whereIn('a.title', [
                                'Interactive Video',
                                'Column Layout',
                                'Interactive Book',
                                'Course Presentation',
                                'Quiz',
                                'Coming Soon!'
                        ])
                        ->get();
                        
        foreach ($demoActivities as $activity) {

            if ($activity->activity_title === 'Interactive Video') {
                $layouts['Interactive Video'] = $activity->demo_activity_id;
            } else if ($activity->activity_title === 'Column Layout') {
                $layouts['Column Layout'] = $activity->demo_activity_id;
            } else if ($activity->activity_title === 'Interactive Book') {
                $layouts['Interactive Book'] = $activity->demo_activity_id;
            } else if ($activity->activity_title === 'Course Presentation') {
                $layouts['Course Presentation'] = $activity->demo_activity_id;
            } else if ($activity->activity_title === 'Quiz') {
                $layouts['Quiz'] = $activity->demo_activity_id;
            } else if ($activity->activity_title === 'Coming Soon!') {
                $layouts['Coming Soon'] = $activity->demo_activity_id;
            }
        }

        $allLayouts = DB::table('activity_layouts')->select('id', 'title')
                        ->whereIn('title', [
                                        'Interactive Video',
                                        'Column Layout',
                                        'Interactive Book',
                                        'Course Presentation',
                                        'Quiz'
                                    ]
                        )
                        ->get();

        $currentDate = now();
        $comingSoonID = (isset($layouts['Coming Soon'])) ? $layouts['Coming Soon'] : 0;

        foreach ($allLayouts as $layout) {

            $demoActivityID = $comingSoonID;
            $demoVideoID = 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs';

            if (isset($layouts[$layout->title])) {
                $demoActivityID = $layouts[$layout->title];
            }
            if (isset($layoutVideoIDs[$layout->title])) {
                $demoVideoID = $layoutVideoIDs[$layout->title];
            }

            DB::table('activity_layouts')
                ->where('id', $layout->id)
                ->update([
                    'demo_activity_id' => $demoActivityID,
                    'demo_video_id' => $demoVideoID,
                    'updated_at' => $currentDate,
                ]);
        }
    }
}
