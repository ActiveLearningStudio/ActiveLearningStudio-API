<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateIvEditorH5pLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pEditorInteractiveVideoParams = ['name' => "H5PEditor.InteractiveVideo", "major_version" => 1, "minor_version" => 24];
        $h5pEditorInteractiveVideoLib = DB::table('h5p_libraries')->where($h5pEditorInteractiveVideoParams)->first();
        if ($h5pEditorInteractiveVideoLib) {
            DB::table('h5p_libraries')->where($h5pEditorInteractiveVideoParams)->update([
                'embed_types' => ''
            ]);
        }
    }
}
