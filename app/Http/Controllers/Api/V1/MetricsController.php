<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\ActivityMetric;
use App\Models\ActivityViewLog;
use App\Models\Playlist;
use App\Models\PlaylistMetric;
use App\Models\PlaylistViewLog;
use App\Models\Project;
use App\Models\ProjectMetric;
use App\Models\ProjectViewLog;


class MetricsController extends Controller
{

    public function activity_logview(Request $req, Activity $activity)
    {
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
        return response(['message' => 'OK'], 200);
    }

    public function playlist_logview(Request $req, Playlist $playlist)
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
        return response(['message' => 'OK'], 200);
    }

    public function project_logview(Request $req, Project $project)
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
        return response(['message' => 'OK'], 200);
    }
}