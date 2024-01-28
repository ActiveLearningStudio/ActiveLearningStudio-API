<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use App\Notifications\ImportUsersRequestProcessedNotification;

class ProcessImportUserRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $organization;

    /**
     * @var
     */
    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $organization, $path)
    {
        $this->user = $user;
        $this->organization = $organization;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepository)
    {
        try {
            $importUsersRequest = $userRepository->processImportUsersRequest($this->organization, $this->path);

            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
            $this->user->notify(new ImportUsersRequestProcessedNotification($userName, $importUsersRequest));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}