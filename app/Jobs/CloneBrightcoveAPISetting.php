<?php

namespace App\Jobs;

use App\Models\Integration\BrightcoveAPISetting;
use App\Models\Organization;
use App\Notifications\CloneNotification;
use App\Repositories\Integration\BrightcoveAPISettingInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneBrightcoveAPISetting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var BrightcoveAPISetting
     */
    protected $brightcoveAPISetting;

    /**
     * @var SubOrganization
     */
    protected $subOrganization;

    /**
     * @var token
     */
    protected $token;

    /**
     * Create a new job instance.
     * @param BrightcoveAPISetting $brightcoveAPISetting
     * @param Organization $suborganization
     * @param $token
     * @return void
     */
    public function __construct(BrightcoveAPISetting $brightcoveAPISetting, Organization $suborganization, $token)
    {
        //
        $this->brightcoveAPISetting = $brightcoveAPISetting;
        $this->subOrganization = $suborganization;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * @param BrightcoveAPISettingInterface $brightcoveAPISettingRepository
     * @param UserRepositoryInterface $userRepository
     * @return void
     */
    public function handle(BrightcoveAPISettingInterface $brightcoveAPISettingRepository, UserRepositoryInterface $userRepository)
    {
        try {
            $brightcoveAPISettingRepository->clone($this->brightcoveAPISetting, $this->subOrganization, $this->token);
            $message = "Your request to clone Brightcove API Setting [" . $this->brightcoveAPISetting->account_name . "] has been completed and available";
            $user_id = $userRepository->parseToken($this->token);
            $user = User::find($user_id);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $user->notify(new CloneNotification($message, 'Clone', $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
