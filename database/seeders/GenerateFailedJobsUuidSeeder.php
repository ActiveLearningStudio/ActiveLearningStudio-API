<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateFailedJobsUuidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('failed_jobs')->whereNull('uuid')->cursor()->each(function ($job) {
            DB::table('failed_jobs')
                ->where('id', $job->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });
    }
}
