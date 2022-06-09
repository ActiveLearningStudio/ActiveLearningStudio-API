<?php

use Illuminate\Database\Seeder;

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

        $layouts = DB::table('activity_layouts')->select('id', 'title', 'order')->get();

        foreach ($layouts as $layout) {

            if(isset($layoutOrder[$layout->title])) {

                DB::table('activity_layouts')
                    ->where('id', $layout->id)
                    ->update([
                        'order' => $layoutOrder[$layout->title],
                    ]);
                    
            }
        }
    }
}
