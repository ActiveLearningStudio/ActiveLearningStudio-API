<?php

namespace App\Repositories\Metrics;

use Illuminate\Http\Request;

use App\User;
use App\Models\Activity;
use App\Models\ActivityMetric;
use App\Models\ActivityViewLog;
use App\Models\Playlist;
use App\Models\PlaylistMetric;
use App\Models\PlaylistViewLog;
use App\Models\Project;
use App\Models\ProjectMetric;
use App\Models\ProjectViewLog;
use App\Repositories\Metrics\MetricsRepositoryInterface;

class MetricsRepository implements MetricsRepositoryInterface
{

    // Returns several project metrics for the specified user
    public function getUserMetrics(User $user){
        $projectIds = $user->projects()->pluck('id');
        $playlistIds = Playlist::whereIn('project_id', $projectIds)->pluck('id');
        $activityIds = Activity::whereIn('playlist_id', $playlistIds)->pluck('id');

        $activityViewsSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('view_count');
        $playlistViewsSum = PlaylistMetric::whereIn('playlist_id', $playlistIds)->sum('view_count');
        $projectViewsSum = ProjectMetric::whereIn('project_id', $projectIds)->sum('view_count');
        
        // These sums are meant for a different way of accounting for "shares" where we count separately each share to each different platform
        // But right now we're only counting whether a project/playlist/activity has been shared or not, not how many platforms it has
        // been shared to.
        //$activitySharesSum = ActivityMetric::whereIn('activity_id', $activityIds)->sum('share_count');
        //$playlistSharesSum = PlaylistMetric::whereIn('playlist_id', $playlistIds)->sum('share_count');
        //$projectSharesSum = ProjectMetric::whereIn('project_id', $projectIds)->sum('share_count');

        // Playlists don't have a shared column so I need to fetch all the shared projects (not just count them) in order to 
        // count the shared playlists
        $sharedProjectsIds = $user->projects()->where('shared', true)->pluck('id');
        $sharedProjectsCount = count($sharedProjectsIds);
        $sharedPlaylistsCount = Playlist::whereIn('project_id', $sharedProjectsIds)->count();
        $sharedActivitiesCount = Activity::whereIn('playlist_id', $playlistIds)->where('shared', true)->count();

        return [
            'project_count' => $projectIds->count(),
            'project_shares' => intval($sharedProjectsCount),
            'project_views' => intval($projectViewsSum),

            'playlist_count' => $playlistIds->count(),
            'playlist_shares' => intval($sharedPlaylistsCount),
            'playlist_views' => intval($playlistViewsSum),

            'activity_count' => $activityIds->count(),
            'activity_shares' => intval($sharedActivitiesCount),
            'activity_views' => intval($activityViewsSum),
        ];
    }

    public function activityLogView(Request $req, Activity $activity){
        $metrics = ActivityMetric::firstOrNew(
            ['activity_id' => $activity->id],
            [
                'view_count' => 0,
                'share_count' => 0,
                'used_storage' => 0,
                'used_bandwidth' => 0,
            ]
        );
        $metrics->view_count++;
        $metrics->save();

        ActivityViewLog::create([
            'activity_id' => $activity->id,
            'message' => json_encode($req->server->all())
        ]);
    }

    public function playlistLogView(Request $req, Playlist $playlist)
    {
        $metrics = PlaylistMetric::firstOrNew(
            ['playlist_id' => $playlist->id],
            [
                'view_count' => 0,
                'share_count' => 0,
                'used_storage' => 0,
                'used_bandwidth' => 0,
            ]
        );
        $metrics->view_count++;
        $metrics->save();

        PlaylistViewLog::create([
            'playlist_id' => $playlist->id,
            'message' => json_encode($req->server->all())
        ]);
    }
    
    public function projectLogView(Request $req, Project $project)
    {
        $metrics = ProjectMetric::firstOrNew(
            ['project_id' => $project->id],
            [
                'view_count' => 0,
                'share_count' => 0,
                'used_storage' => 0,
                'used_bandwidth' => 0,
            ]
        );
        $metrics->view_count++;
        $metrics->save();

        ProjectViewLog::create([
            'project_id' => $project->id,
            'message' => json_encode($req->server->all())
        ]);
    }
}
