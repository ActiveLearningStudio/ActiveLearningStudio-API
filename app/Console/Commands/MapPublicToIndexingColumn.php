<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class MapPublicToIndexingColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'public:indexing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is one time script to map the public column to indexing column.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $indexedProjects = Project::where('is_public', true)->where('elasticsearch', true); // get already indexed projects
        $publicProjects = Project::where('is_public', true); // projects which are public
        $indexedProjects->update(['indexing' => config('constants.indexing-approved')]); // these projects are approved for indexing
        $publicProjects->update(['status' => 2]); // these projects are finalized/finished
    }
}
