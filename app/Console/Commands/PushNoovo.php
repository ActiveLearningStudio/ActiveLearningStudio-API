<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Team\TeamRepositoryInterface;


class PushNoovo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:noovo';
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
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        parent::__construct();
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
