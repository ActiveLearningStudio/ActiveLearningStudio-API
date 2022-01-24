<?php

namespace App\Jobs;

use App\Models\Integration\KalturaAPISetting;
use App\Models\Organization;
use App\Notifications\CloneNotification;
use App\Repositories\Integration\KalturaAPISettingInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneKalturaAPISetting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var kalturaAPISetting
     */
    protected $kalturaAPISetting;

    /**
     * @var subOrganization
     */
    protected $subOrganization;

    /**
     * @var token
     */
    protected $token;

    /**
     * Create a new job instance.
     * @param KalturaAPISetting $kalturaAPISetting
     * @param Organization $suborganization
     * @param $token
     * @return void
     */
    public function __construct(KalturaAPISetting $kalturaAPISetting, Organization $suborganization, $token)
    {
        //
        $this->kalturaAPISetting = $kalturaAPISetting;
        $this->subOrganization = $suborganization;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * @param KalturaAPISettingInterface $kalturaAPISettingRepository
     * @param UserRepositoryInterface $userRepository
     * @return void
     */
    public function handle(KalturaAPISettingInterface $kalturaAPISettingRepository, UserRepositoryInterface $userRepository)
    {
        try {
            $kalturaAPISettingRepository->clone($this->kalturaAPISetting, $this->subOrganization, $this->token);
            $message = "Your request to clone Kaltura API Setting [" . $this->kalturaAPISetting->name . "|" . $this->kalturaAPISetting->partner_id . "] has been completed and available";
            $user_id = $userRepository->parseToken($this->token);
            $user = User::find($user_id);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $user->notify(new CloneNotification($message, 'Clone', $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
