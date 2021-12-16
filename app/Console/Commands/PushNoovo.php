<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Services\NoovoCMSService;
use App\Jobs\ExportProjecttoNoovo;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Team;
use App\User;

class PushNoovo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:noovo';
    private $noovoCMSService;
    private $teamRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Curriki Projects into Noovo CMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NoovoCMSService $noovoCMSService, TeamRepositoryInterface $teamRepository)
    {
        parent::__construct();
        $this->noovoCMSService = $noovoCMSService;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $this->teamRepository->noovoIntegration();       
        
    }
}
