<?php

namespace App\Repositories\Metrics;

use App\User;
use App\Models\Activity;
use App\Models\ActivityMetric;
use App\Models\Playlist;
use App\Models\PlaylistMetric;
use App\Models\Project;
use App\Models\ProjectMetric;

class MetricsRepository
{

    // Returns several project metrics for the specified user
    public function getUserMetrics(User $user){
        $projectIds = $user->projects()->pluck('id');
        $playlistIds = Playlist::whereIn('project_id', $projectIds)->pluck('id');
        $activityIds = Activity::whereIn('playlist_id', $playlistIds)->pluck('id');
        $activityViewsSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('view_count');
        $activitySharesSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('share_count');

        $playlistViewsSum = PlaylistMetric::whereIn('playlist_id', $playlistIds)->sum('view_count');
        $playlistSharesSum = PlaylistMetric::whereIn('playlist_id', $playlistIds)->sum('share_count');

        $projectViewsSum = ProjectMetric::whereIn('project_id', $projectIds)->sum('view_count');
        $projectSharesSum = ProjectMetric::whereIn('project_id', $projectIds)->sum('share_count');

        return [
            'project_count' => $projectIds->count(),
            'project_shares' => $projectSharesSum,
            'project_views' => $projectViewsSum,

            'playlist_count' => $playlistIds->count(),
            'playlist_shares' => $playlistSharesSum,
            'playlist_views' => $playlistViewsSum,

            'activity_count' => $activityIds->count(),
            'activity_shares' => $activitySharesSum,
            'activity_views' => $activityViewsSum,
        ];
    }
    
}
