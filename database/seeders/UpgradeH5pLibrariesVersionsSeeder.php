<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpgradeH5pLibrariesVersionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentDate = now();
        // update column layout 1.13 to 1.15 in activity layouts
        $columnLayoutActivityLayoutsParams = ['title' => 'Column Layout', 'h5pLib' => 'H5P.Column 1.13'];
        $columnLayoutActivityLayout = DB::table('activity_layouts')->where($columnLayoutActivityLayoutsParams)->first();
        if ($columnLayoutActivityLayout) {
            DB::table('activity_layouts')->where($columnLayoutActivityLayoutsParams)->update([
                'h5pLib' => 'H5P.Column 1.15',
                'updated_at' => $currentDate
            ]);
        }
        // update interactive Book to 1.3 to 1.6 in activity layouts
        $ibActivityLayoutsParams = ['title' => 'Interactive Book', 'h5pLib' => 'H5P.InteractiveBook 1.3'];
        $ibActivityLayout = DB::table('activity_layouts')->where($ibActivityLayoutsParams)->first();
        if ($ibActivityLayout) {
            DB::table('activity_layouts')->where($ibActivityLayoutsParams)->update([
                'h5pLib' => 'H5P.InteractiveBook 1.6',
                'updated_at' => $currentDate
            ]);
        }

        // update Question Set to 1.17 to 1.20 in activity layouts
        $quizActivityLayoutsParams = ['title' => 'Quiz', 'h5pLib' => 'H5P.QuestionSet 1.17'];
        $quizActivityLayout = DB::table('activity_layouts')->where($quizActivityLayoutsParams)->first();
        if ($quizActivityLayout) {
            DB::table('activity_layouts')->where($quizActivityLayoutsParams)->update([
                'h5pLib' => 'H5P.QuestionSet 1.20',
                'updated_at' => $currentDate
            ]);
        }
  
        $activityItems = [
            [
                'title' => 'Column Layout',
                'currentH5pLib' => 'H5P.Column 1.13',
                'newH5pLib' => 'H5P.Column 1.15',
            ],
            [
                'title' => 'Interactive Book',
                'currentH5pLib' => 'H5P.InteractiveBook 1.3',
                'newH5pLib' => 'H5P.InteractiveBook 1.6',
            ],
            [
                'title' => 'Quiz',
                'currentH5pLib' => 'H5P.QuestionSet 1.17',
                'newH5pLib' => 'H5P.QuestionSet 1.20',
            ],
            [
                'title' => 'Essay',
                'currentH5pLib' => 'H5P.Essay 1.2',
                'newH5pLib' => 'H5P.Essay 1.5',
            ],
            [
                'title' => 'Guess The Answer',
                'currentH5pLib' => 'H5P.GuessTheAnswer 1.4',
                'newH5pLib' => 'H5P.GuessTheAnswer 1.5',
            ],
            [
                'title' => 'Image Hotspot',
                'currentH5pLib' => 'H5P.ImageHotspots 1.8',
                'newH5pLib' => 'H5P.ImageHotspots 1.10',
            ]
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
}
