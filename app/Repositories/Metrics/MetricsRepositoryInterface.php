<?php

namespace App\Repositories\Metrics;

use Illuminate\Http\Request;

use App\User;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;

interface MetricsRepositoryInterface
{
    /**
     * Get some accounting metrics for a particular user
     * @param User $user
     */
    public function getUserMetrics(User $user);

    /**
     * Log that an activity has been viewed
     * @param \App\Repositories\Project\Request $request
     * @param Activity $activity
     */    
    public function activityLogView(Request $req, Activity $activity);

    /**
     * Log that a playlist has been viewed
     * @param \App\Repositories\Project\Request $request
     * @param Playlist $playlist
     */    
    public function playlistLogView(Request $req, Playlist $playlist);

    /**
     * Log that a project has been viewed
     * @param \App\Repositories\Project\Request $request
     * @param Project $project
     */    
    public function projectLogView(Request $req, Project $project);
}
