<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Activity;
use App\Models\ActivityMetrics;

class MetricsController extends Controller
{

    public function logview(Activity $activity)
    {
        $metrics = ActivityMetrics::firstOrNew(
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
        return response(['message' => 'OK'], 200);
    }
}