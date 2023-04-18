<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateActivityItemsDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activityItems = [];
        $activityItemsDescriptions = [];

        if (file_exists(public_path('sample/activity-items-description.csv'))) {
            $file = fopen(public_path('sample/activity-items-description.csv'), 'r');
            $index = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                if ($line[0]!== '' && $line[1]!== '') {
                  
                    $activityItems[$index] = strtolower(trim($line[0]));
                    $activityItemsDescriptions[$index] = trim($line[1]);

                } 
                $index++;
            }
            fclose($file);
        } else {
            exit();
        }
        
        $allActivityItems = DB::table('activity_items')->select('id', 'title')->get();

        foreach ($allActivityItems as $activityItem) {
            
            $description = '';
            $foundedKey = array_search(strtolower($activityItem->title), $activityItems);
            
            if ($foundedKey) {
                $description = $activityItemsDescriptions[$foundedKey];
          
                DB::table('activity_items')
                    ->where('id', $activityItem->id)
                    ->update([
                        'description' => $description
                    ]);
            } 
        }
        
        // update descriptions for layouts
        $allActivityLayouts = DB::table('activity_layouts')->select('id', 'title')->get();

        foreach ($allActivityLayouts as $activityLayout) {
            
            $description = '';
            $foundedKey = array_search(strtolower($activityLayout->title), $activityItems);
            
            if ($foundedKey) {
                $description = $activityItemsDescriptions[$foundedKey];
          
                DB::table('activity_layouts')
                    ->where('id', $activityLayout->id)
                    ->update([
                        'description' => $description
                    ]);
            } 
        } 
    }
    

}
