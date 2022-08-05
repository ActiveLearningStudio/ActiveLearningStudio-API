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


        // update fill in the blanks 1.12 to 1.14 in activity items
        $fillInTheBlanksActivityItemsParams = ['title' => 'Fill In The Blanks', 'h5pLib' => 'H5P.Blanks 1.12'];
        $fillInTheBlanksActivityItems = DB::table('activity_items')->where($fillInTheBlanksActivityItemsParams)->first();
        if ($fillInTheBlanksActivityItems) {
            DB::table('activity_items')->where($fillInTheBlanksActivityItemsParams)->update([
                'h5pLib' => 'H5P.Blanks 1.14',
                'updated_at' => $currentDate
            ]);
        }

        // update true false 1.6 to 1.8 in activity items
        $trueFalseActivityItemsParams = ['title' => 'True & False', 'h5pLib' => 'H5P.TrueFalse 1.6'];
        $trueFalseActivityItems = DB::table('activity_items')->where($trueFalseActivityItemsParams)->first();
        if ($trueFalseActivityItems) {
            DB::table('activity_items')->where($trueFalseActivityItemsParams)->update([
                'h5pLib' => 'H5P.TrueFalse 1.8',
                'updated_at' => $currentDate
            ]);
        }

        // update Drag & Drop 1.13 to 1.14 in activity items
        $dragDropActivityItemsParams = ['title' => 'Drag & Drop', 'h5pLib' => 'H5P.DragQuestion 1.13'];
        $dragDropActivityItems = DB::table('activity_items')->where($dragDropActivityItemsParams)->first();
        if ($dragDropActivityItems) {
            DB::table('activity_items')->where($dragDropActivityItemsParams)->update([
                'h5pLib' => 'H5P.DragQuestion 1.14',
                'updated_at' => $currentDate
            ]);
        }


        // update Mark The Words 1.9 to 1.11 in activity items
        $markTheWordsActivityItemsParams = ['title' => 'Mark The Words', 'h5pLib' => 'H5P.MarkTheWords 1.9'];
        $markTheWordsActivityItems = DB::table('activity_items')->where($markTheWordsActivityItemsParams)->first();
        if ($markTheWordsActivityItems) {
            DB::table('activity_items')->where($markTheWordsActivityItemsParams)->update([
                'h5pLib' => 'H5P.MarkTheWords 1.11',
                'updated_at' => $currentDate
            ]);
        }

        // update Dialog cards 1.8 to 1.9 in activity items
        $dialogCardsActivityItemsParams = ['title' => 'Dialog Cards', 'h5pLib' => 'H5P.Dialogcards 1.8'];
        $dialogCardsActivityItems = DB::table('activity_items')->where($dialogCardsActivityItemsParams)->first();
        if ($dialogCardsActivityItems) {
            DB::table('activity_items')->where($dialogCardsActivityItemsParams)->update([
                'h5pLib' => 'H5P.Dialogcards 1.9',
                'updated_at' => $currentDate
            ]);
        }

        // update Drag Text 1.8 to 1.10 in activity items
        $dragTextActivityItemsParams = ['title' => 'Drag Text', 'h5pLib' => 'H5P.DragText 1.8'];
        $dragTexActivityItems = DB::table('activity_items')->where($dragTextActivityItemsParams)->first();
        if ($dragTexActivityItems) {
            DB::table('activity_items')->where($dragTextActivityItemsParams)->update([
                'h5pLib' => 'H5P.DragText 1.10',
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
