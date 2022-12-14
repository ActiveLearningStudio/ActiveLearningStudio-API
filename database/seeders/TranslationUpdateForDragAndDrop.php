<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationUpdateForDragAndDrop extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $translation = '{
            "libraryStrings": {
              "insertElement": "Add :type",
              "done": "Done",
              "remove": "Remove",
              "image": "Image",
              "text": "Text",
              "noTaskSize": "Please specify task size first.",
              "confirmRemoval": "Are you sure you wish to remove this element?",
              "backgroundOpacityOverridden": "The background opacity is overridden",
              "advancedtext": "Text",
              "dropzone": "Drop Zone",
              "selectAll": "Select all",
              "deselectAll": "Deselect all",
              "deleteTaskTitle": "Deleting task",
              "cancel": "Cancel",
              "confirm": "Confirm",
              "ok": "Ok"
            }
          }' ;
        // 
       $id = DB::table('h5p_libraries')->
        where([ ['name', 'H5PEditor.DragQuestion'], 
                ['major_version',1], 
                ['minor_version', 10]])->first();
        
        DB::table('h5p_libraries_languages')->
        where([['library_id',$id->id],['language_code','en']])->
        update(['translation' => $translation]);
    }
}
