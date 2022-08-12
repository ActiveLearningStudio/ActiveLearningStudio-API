<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpgradeH5pVersionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = now();

        $this->branchingScenarioSetDemoIdAndUrl($currentDate);



        // update course presentation 1.22 to 1.24 in activity layouts
        $coursePresentationActivityLayoutsParams = ['title' => 'Course Presentation', 'h5pLib' => 'H5P.CoursePresentation 1.22'];
        $coursePresentationActivityLayouts = DB::table('activity_layouts')->where($coursePresentationActivityLayoutsParams)->first();
        
        if ($coursePresentationActivityLayouts) {
            DB::table('activity_layouts')->where($coursePresentationActivityLayoutsParams)->update([
                'h5pLib' => 'H5P.CoursePresentation 1.24',
                'updated_at' => $currentDate
            ]);
        }

        $activityItems = [
            [
                'title' => 'Fill In The Blanks',
                'currentH5pLib' => 'H5P.Blanks 1.12',
                'newH5pLib' => 'H5P.Blanks 1.14',
            ],
            [
                'title' => 'True & False',
                'currentH5pLib' => 'H5P.TrueFalse 1.6',
                'newH5pLib' => 'H5P.TrueFalse 1.8',
            ],
            [
                'title' => 'Drag & Drop',
                'currentH5pLib' => 'H5P.DragQuestion 1.13',
                'newH5pLib' => 'H5P.DragQuestion 1.14',
            ],
            [
                'title' => 'Mark The Words',
                'currentH5pLib' => 'H5P.MarkTheWords 1.9',
                'newH5pLib' => 'H5P.MarkTheWords 1.11',
            ],
            [
                'title' => 'Dialog Cards',
                'currentH5pLib' => 'H5P.Dialogcards 1.8',
                'newH5pLib' => 'H5P.Dialogcards 1.9',
            ],
            [
                'title' => 'Drag Text',
                'currentH5pLib' => 'H5P.DragText 1.8',
                'newH5pLib' => 'H5P.DragText 1.10',
            ],

        ];


        foreach ($activityItems as $activityItem) {
            DB::table('activity_items')
                ->where(['title' => $activityItem['title'], 'h5pLib' => $activityItem['currentH5pLib']])
                ->update([
                    'h5pLib' => $activityItem['newH5pLib'],
                    'updated_at' => $currentDate
                ]);
        }


    }

    private function branchingScenarioSetDemoIdAndUrl($currentDate)
    {
        $sampleProjectName = config('constants.curriki-sample-project');
        $demoUserEmail = config('constants.curriki-demo-email');
        $organizationId = DB::table('organizations')->where('domain', 'currikistudio')->value('id');


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
                'Coming Soon!'
            ])
            ->get();

        $demoActivityId = 0;
        $demoVideoId = 'https://www.youtube-nocookie.com/embed/F0P53KBqYSs';
        foreach ($demoActivities as $activity) {
            if ($activity->activity_title === 'Coming Soon!') {
                $demoActivityId = $activity->demo_activity_id;
            }
        }

        // update demo activity ids and urls
        $branchingScenarioActivityLayoutsParams = ['title' => 'Branching Scenario', 'h5pLib' => 'H5P.BranchingScenario 1.7'];
        $branchingScenarioActivityLayouts = DB::table('activity_layouts')->where($branchingScenarioActivityLayoutsParams)->first();

        if ($branchingScenarioActivityLayouts) {
            DB::table('activity_layouts')->where($branchingScenarioActivityLayoutsParams)->update([
                'demo_activity_id' => $demoActivityId,
                'demo_video_id' => $demoVideoId,
                'updated_at' => $currentDate
            ]);
        }

        $branchingScenarioActivityItemsParams = ['title' => 'Branching Scenario (Beta)', 'h5pLib' => 'H5P.BranchingScenario 1.7'];
        $branchingScenarioActivityItems = DB::table('activity_items')->where($branchingScenarioActivityItemsParams)->first();
        if ($branchingScenarioActivityItems) {
            DB::table('activity_items')->where($branchingScenarioActivityItemsParams)->update([
                'demo_activity_id' => $demoActivityId,
                'demo_video_id' => $demoVideoId,
                'updated_at' => $currentDate
            ]);
        }

    }
}
