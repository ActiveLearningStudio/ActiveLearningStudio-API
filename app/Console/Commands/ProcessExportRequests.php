<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepositoryInterface;

class ProcessExportRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:export-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the requests to export users projects and independent activities.';

    /**
     * Execute the console command.
     *
     * @param UserRepositoryInterface $userRepository
     * @return int
     */
    public function handle(UserRepositoryInterface $userRepository)
    {
        return Command::SUCCESS;
    }
}
