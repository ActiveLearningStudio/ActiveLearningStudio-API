<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;

class MoveIndependentActivitiesToActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:independent-activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move existing independent activities to activities';

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
     * @return int
     */
    public function handle(
        IndependentActivityRepositoryInterface $independentActivityRepository
    )
    {
        return $independentActivityRepository->copyToActivity();
    }
}
