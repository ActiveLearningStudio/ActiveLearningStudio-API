<?php

namespace App\Console\Commands;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Console\Command;

class EliminateDualSignups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eliminate:dual-signups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will detect the multiple user signups with case sensitivity difference and then swap the id of first signup with the id of second signup';

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
    public function handle(UserRepositoryInterface $userRepository)
    {
        return $userRepository->eliminateDualSignup();
    }
}
