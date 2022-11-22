<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\MicrosoftTeam\MicrosoftTeamRepositoryInterface;
use App\Models\Playlist;
use App\User;
use App\Notifications\PlaylistPublishNotification;

class PublishPlaylist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Playlist
     */
    protected $playlist;

    /**
     * @var string
     */
    protected $classId;

    /**
     * @var string
     */
    protected $aSyncUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Playlist $playlist, $classId)
    {
        $this->user = $user;
        $this->playlist = $playlist;
        $this->classId = $classId;
        $this->aSyncUrl = '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MicrosoftTeamRepositoryInterface $microsoftTeamRepository)
    {
        try {
            if(empty($this->classId)) {
                $response = $microsoftTeamRepository->createMsTeamClass($this->user->msteam_access_token, ['displayName'=>$this->playlist->title]);    
                \Log::info($response);
                $class = json_decode($response, true);
                $this->classId = $class['classId'];
                $this->aSyncUrl = $class['aSyncURL'];
            }
            
            $microsoftTeamRepository->createMSTeamAssignmentPlaylist($this->user->msteam_access_token, $this->classId, $this->playlist, $this->aSyncUrl);
            $userName = rtrim($this->user->first_name . ' ' . $this->user->last_name, ' ');
           
            $this->user->notify(new PlaylistPublishNotification($userName, $this->playlist->title));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
