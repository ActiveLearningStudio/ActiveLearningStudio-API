<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ErrorController extends Controller
{
    /**
     * ErrorController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Display error
     *
     * @response 401 {
     *   "errors": [
     *     "Unauthorized."
     *   ]
     * }
     *
     * @return Response
     */
    public function show()
    {
        return response([
            'errors' => ['Unauthorized.'],
        ], 401);
    }
}
