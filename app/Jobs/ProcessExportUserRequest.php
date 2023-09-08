<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use App\Notifications\ExportUsersRequestProcessedNotification;

class ProcessExportUserRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var 
     */
    protected $path;

    /**
     * @var
     */
    protected $organization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $path, $organization = null)
    {
        $this->user = $user;
        $this->path = $path;
        $this->organization = $organization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepository)
    {
        try {
            $exportUsersRequest = $userRepository->processExportUsersRequest($this->user, $this->path, $this->organization);

            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
            $this->user->notify(new ExportUsersRequestProcessedNotification($userName, $exportUsersRequest));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}