<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ExtractXAPIJSON implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param ActivityRepositoryInterface $activityRepository
     * @return void
     */
    public function handle(ActivityRepositoryInterface $activityRepository)
    {
        $offset = 10;
        $limit = 1;
        $xapi_statements = DB::connection('lrs_pgsql')->table(config('xapi.lrs_db_statements_table'))->select()
                ->offset($offset)
                ->limit($limit)
                ->get();

        foreach ($xapi_statements as $statement) {
            \Log::info('this is new statement: ', print_r($statement, true));
            
            exit;
        }
    }
}
