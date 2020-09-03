<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Http\Models\H5pLibrary;
use App\Repositories\H5pLibrary\H5pLibraryRepositoryInterface;

class H5pElasticsearchFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param H5pLibraryRepositoryInterface $h5pLibraryRepository
     * @return void
     */
    public function run(H5pLibraryRepositoryInterface $h5pLibraryRepository)
    {
        $h5pLibraries = $h5pLibraryRepository->all();

        foreach ($h5pLibraries as $h5pLibrary) {
            $h5pElasticsearchFields = $h5pLibraryRepository->getFieldSemantics($h5pLibrary);

            if ($h5pElasticsearchFields) {
                DB::table('h5p_elasticsearch_fields')->insert($h5pElasticsearchFields);
            }
        }
    }
}
