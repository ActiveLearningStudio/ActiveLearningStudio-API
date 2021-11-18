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
     * Create a new job instance.
     * @param LtiToolSetting $ltiToolSetting
     * @param $token
     * @return void
     */
    public function __construct(LtiToolSetting $ltiToolSetting, Organization $suborganization, $token)
    {
        //
        $this->ltiToolSetting = $ltiToolSetting;
        $this->subOrganization = $suborganization;
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
            $ltiToolSettingRepository->clone($this->ltiToolSetting, $this->subOrganization, $this->token);
            $message = "Your request to clone Lti Tool Setting [" . $this->ltiToolSetting->tool_name . "] has been completed and available";
            $user_id = $userRepository->parseToken($this->token);
            $user = User::find($user_id);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $user->notify(new CloneNotification($message, 'Clone', $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
