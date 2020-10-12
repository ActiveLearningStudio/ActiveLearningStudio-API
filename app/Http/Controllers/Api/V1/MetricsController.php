<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Metrics\MetricsRepositoryInterface;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    private $metricsRepository;

    public function __construct(MetricsRepositoryInterface $metricsRepository)
    {
        $this->metricsRepository = $metricsRepository;
    }

    public function activityLogView(Request $req, Activity $activity)
    {
        $this->metricsRepository->activityLogView($req, $activity);
        return response(['message' => 'OK'], 200);
    }

    public function playlistLogView(Request $req, Playlist $playlist)
    {
        $this->metricsRepository->playlistLogView($req, $playlist);
        return response(['message' => 'OK'], 200);
    }

    public function projectLogView(Request $req, Project $project)
    {
        $this->metricsRepository->projectLogView($req, $project);
        return response(['message' => 'OK'], 200);
    }
}
