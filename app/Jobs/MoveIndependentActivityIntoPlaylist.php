<?php

namespace App\Jobs;

use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Models\IndependentActivity;
use App\Models\Organization;
use App\Models\Playlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\CloneNotification;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\User;

class MoveIndependentActivityIntoPlaylist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var IndependentActivity
     */
    protected $independentActivity;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var Playlist
     */
    protected $playlist;

    /**
     * @var
     */
    protected $token;
    
    /**
     * Create a new job instance.
     *
     * @param Organization $organization
     * @param IndependentActivity $independentActivity
     * @param $token
     */
    public function __construct(
        Organization $organization,
        IndependentActivity $independentActivity,
        Playlist $playlist,
        $token
    )
    {
        $this->organization = $organization;
        $this->independentActivity = $independentActivity;
        $this->playlist = $playlist;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @param IndependentActivityRepositoryInterface $independentActivityRepository
     * @param UserRepositoryInterface $userRepository
     * @return void
     */
    public function handle(
        IndependentActivityRepositoryInterface $independentActivityRepository, 
        UserRepositoryInterface $userRepository
    )
    {
        try {
            $independentActivityRepository->moveToPlaylist($this->independentActivity, $this->playlist, $this->token);
            $message = "Your request to add independent activity [" . $this->independentActivity->title . "] into 
                            playlist [" . $this->playlist->title . "] has been completed and available";
            $userId = $userRepository->parseToken($this->token);
            $user = User::find($userId);
            $userName = rtrim($user->first_name . ' ' . $user->last_name, ' ');
            $process = "Move Independent Activity into Playlist";
            $user->notify(new CloneNotification($message, $process, $userName));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
