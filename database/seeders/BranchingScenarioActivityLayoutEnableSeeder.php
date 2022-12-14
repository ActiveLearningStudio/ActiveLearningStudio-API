<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchingScenarioActivityLayoutEnableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // activating branching scenario activity layouts
        $branchingScenarioActivityLayoutsParams = ['title' => 'Branching Scenario', 'h5pLib' => 'H5P.BranchingScenario 1.7'];
        $branchingScenarioActivityLayouts = DB::table('activity_layouts')->where($branchingScenarioActivityLayoutsParams)->first();
        
        if ($branchingScenarioActivityLayouts) {
            DB::table('activity_layouts')->where($branchingScenarioActivityLayoutsParams)->update([
                'deleted_at' => null
            ]);
        }

        $branchingScenarioActivityItemsParams = ['title' => 'Branching Scenario (Beta)', 'h5pLib' => 'H5P.BranchingScenario 1.7'];
        $branchingScenarioActivityItems = DB::table('activity_items')->where($branchingScenarioActivityItemsParams)->first();
        if ($branchingScenarioActivityItems) {
            DB::table('activity_items')->where($branchingScenarioActivityItemsParams)->update([
                'title' => 'Branching Scenario',
                'description' => 'Create adaptive scenarios for the learner'
            ]);
        }

    }
}
