<?php

namespace App\Jobs;

use App\Repositories\Playlist\PlaylistRepositoryInterface;
use App\Models\Playlist;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClonePlayList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * @var Project
     */
    protected $project;
    
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
     * @return void
     */
    public function __construct(Project $project, Playlist $playlist, $token)
    {
        $this->project = $project;
        $this->playlist = $playlist;
        $this->token = $token;
    }

    /**
     * Execute the job.
     * @param PlaylistRepositoryInterface $playlistRepository
     * @return void
     */
    public function handle(PlaylistRepositoryInterface $playlistRepository)
    {
        $playlistRepository->clone($this->project, $this->playlist, $this->token);
    }
}
