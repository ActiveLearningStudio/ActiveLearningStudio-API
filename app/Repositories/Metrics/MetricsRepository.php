<?php

namespace App\Repositories\Metrics;

use App\User;
use App\Models\Playlist;
use App\Models\Activity;
use App\Models\ActivityMetric;

class MetricsRepository
{

    // Returns several project metrics for the specified user
    public function getUserMetrics(User $user){
        $projectIds = $user->projects()->pluck('id');
        $playlistIds = Playlist::whereIn('project_id', $projectIds)->pluck('id');
        $activityIds = Activity::whereIn('playlist_id', $playlistIds)->pluck('id');
        $activityViewsSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('view_count');
        $activitySharesSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('share_count');

        return [
            'project_count' => $projectIds->count(),
            'project_shares' => 0,
            'project_views' => 0,

            'playlist_count' => $playlistIds->count(),
            'playlist_shares' => 0,
            'playlist_views' => 0,

            'activity_count' => $activityIds->count(),
            'activity_shares' => $activitySharesSum,
            'activity_views' => $activityViewsSum,
        ];
    }
    
}
