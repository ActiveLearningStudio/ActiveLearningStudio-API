<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLayoutAndActivityItemsOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layoutOrder = [
            'Interactive Video' => 1,
            'Course Presentation' => 2,
            'Column Layout' => 3,
            'Quiz' => 4,
            'Interactive Book' => 5,
        ];

        foreach ($layoutOrder as $title => $order) {
            DB::table('activity_layouts')
                ->where('title', $title)
                ->update([
                    'order' => $order
                ]);
        }
    
    }
}
