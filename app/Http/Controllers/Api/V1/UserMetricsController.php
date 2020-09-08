<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Metrics\MetricsRepository;
use App\User;

use Illuminate\Http\Response;

class UserMetricsController extends Controller
{
    private $metricsRepository;

    public function __construct(MetricsRepository $metricsRepository)
    {
        $this->metricsRepository = $metricsRepository;
    }

    // Returns usage metrics for a particular user
    public function show(User $user)
    {
        $authenticated_user = auth()->user();

        if (!$authenticated_user->isAdmin() && $authenticated_user->id != $user->id) {
            return response([
                'errors' => ['Unauthorized.'],
            ], 401);
        }

        return response([
            'metrics' => $this->metricsRepository->getUserMetrics($user),
        ], 200);
    }
}
