<?php

use Illuminate\Database\Seeder;

class DemoActivityIDsAssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return DB::transaction(function () {

            $sampleProjectName = 'Activity Sampler';
            $demoUserEmail = 'demo@currikistudio.org';
            $organizationId = DB::table('organizations')->where('domain', 'currikistudio')->value('id');

            $layoutVideoIDs = [
                'Interactive Video' => 'https://www.youtube-nocookie.com/embed/7FnoeS8fxXg',
                'Column Layout' => 'https://www.youtube-nocookie.com/embed/ngXSzWNYzU4',
                'Course Presentation' => 'https://www.youtube-nocookie.com/embed/b1_-JJWKh3w',
                'Interactive Book' => 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs',
                'Quiz' => 'https://www.youtube-nocookie.com/embed/t0vsfxiq1zk',
            ];

            $layouts = [];

            $demoActivities = DB::table('activities as a')
                                ->select(
                                    'a.id as demo_activity_id',
                                    'a.title as activity_title',
                                    'proj.name',
                                    'u.email',
                                    'u.id as user_id'
                            )
                            ->leftJoin('playlists as p', 'p.id', '=', 'a.playlist_id')
                            ->leftJoin('projects as proj', 'proj.id', '=', 'p.project_id')
                            ->leftJoin('user_project as up', 'up.project_id', '=', 'proj.id')
                            ->leftJoin('users as u', 'u.id', '=', 'up.user_id')
                            ->where('u.email', $demoUserEmail)
                            ->where('proj.organization_id', $organizationId)
                            ->where('proj.name', $sampleProjectName)
                            ->whereIn('a.title', [
                                    'Interactive Video',
                                    'Column Layout',
                                    'Interactive Book',
                                    'Course Presentation',
                                    'Quiz'
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

            foreach ($allLayouts as $layout) {
                $demoActivityID = 0;
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

        });
    }
}
