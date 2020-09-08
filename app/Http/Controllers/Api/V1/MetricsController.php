<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityMetric;
use App\Models\ActivityViewLog;

class MetricsController extends Controller
{

    public function logview(Request $req, Activity $activity)
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
}