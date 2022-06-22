<?php

use Illuminate\Database\Seeder;

class UpdateActivityLayoutsDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemsDescriptions = DB::table('activity_items')
                                        ->where('organization_id', 1)
                                        ->WhereIn('title', [
                                            'Column Layout',
                                            'Interactive Book',
                                            'Interactive Video',
                                            'Course Presentation',
                                            'Quiz'
                                        ])
                                        ->pluck('description', 'title');

        foreach ($itemsDescriptions as $title => $description) {
            DB::table('activity_layouts')
                ->where('title', $title)
                ->update([
                    'description' => $description
                ]);
        }
    }
}
