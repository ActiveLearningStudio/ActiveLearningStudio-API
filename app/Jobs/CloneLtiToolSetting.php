<?php

namespace App\Jobs;

use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Notifications\CloneNotification;
use App\Repositories\LtiTool\LtiToolSettingInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CloneLtiToolSetting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var LtiToolSetting
     */
    protected $ltiToolSetting;

    /**
     * @var SubOrganization
     */
    protected $subOrganization;

    /**
     * @var token
     */
    protected $token;

    /**
     * @var userId
     */
    protected $userId;

    /**
     * Create a new job instance.
     * @param LtiToolSetting $ltiToolSetting
     * @param Organization $suborganization
     * @param $userId
     * @param $token
     * @return void
     */
    public function __construct(LtiToolSetting $ltiToolSetting, Organization $suborganization, $userId, $token)
    {
        //
        $this->ltiToolSetting = $ltiToolSetting;
        $this->subOrganization = $suborganization;
        $this->userId = $userId;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * @param LtiToolSettingInterface $ltiToolSettingRepository
     * @param UserRepositoryInterface $userRepository
     * @return void
     */
    public function handle(LtiToolSettingInterface $ltiToolSettingRepository, UserRepositoryInterface $userRepository)
    {
        try {
            $ltiToolSettingRepository->clone($this->ltiToolSetting, $this->subOrganization, $this->userId);
            $message = "Your request to clone Lti Tool Setting [" . $this->ltiToolSetting->tool_name . "] has been completed and available";
            $loggedUserId = $userRepository->parseToken($this->token);
            $loggedUser = User::find($loggedUserId);
            $loggedUserName = rtrim($loggedUser->first_name . ' ' . $loggedUser->last_name, ' ');
            $loggedUser->notify(new CloneNotification($message, 'Clone', $loggedUserName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
