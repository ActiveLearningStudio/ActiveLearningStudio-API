<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WhiteboardRequest;
use App\Services\WhiteboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group 28. Admin/Whiteboard
 *
 * APIs for whiteboard.
 */
class WhiteboardController extends Controller
{
    private $whiteboardService;

    const FILE_NAME = "whiteboard.json";

    /**
     * WhiteboardController constructor.
     * @param WhiteboardService $whiteboardService
     */
    public function __construct(WhiteboardService $whiteboardService)
    {
        $this->whiteboardService = $whiteboardService;
    }

    /**
     * Get Whiteboard.
     *
     * Get Whiteboard.
     *
     * @param WhiteboardRequest $request
     *
     * @return mixed
     */
    public function getWhiteboard(WhiteboardRequest $request)
    {
        $data = $request->all();
        $response =  $this->getWhiteboardURL($data);

        if ($response->status() === 401) {
            $this->regenerateAccessToken();
            $response =  $this->getWhiteboardURL($data);
        }

        return $response->json();
    }

    /**
     * Get Whiteboard URL.
     *
     * Get Whiteboard URL.
     *
     * @param $data
     * @return mixed
     */
    public function getWhiteboardURL($data)
    {
        $access_token = $this->getAccessToken();
        return $this->whiteboardService->getWhiteboardURL($data, $access_token);
    }

    /**
     * Get access token
     *
     * Get access token for whiteboard.
     *
     * @return string
     * @throws GeneralException
     */
    public function getAccessToken()
    {
        try {
            // Read File
            if (!file_exists(base_path('storage/' . self::FILE_NAME))) {
                $this->regenerateAccessToken();
            }
            $jsonString = file_get_contents(base_path('storage/' . self::FILE_NAME));
            $data = json_decode($jsonString, true);

            return $data['access_token'];

        } catch (\Exception $e) {
            Log::Error('Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Update access token
     *
     * Update access token for whiteboard.
     *
     * @return string
     * @throws GeneralException
     */
    public function regenerateAccessToken()
    {
        try {
            $access_token = $this->whiteboardService->regenerateAccessToken();

            // Read File
            if (!file_exists(base_path('storage/' . self::FILE_NAME))) {
                $data['access_token'] = $access_token;
                // Write File
                $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
                return file_put_contents(base_path('storage/' . self::FILE_NAME), stripslashes($newJsonString));
            }

            $jsonString = file_get_contents(base_path('storage/' . self::FILE_NAME));
            $data = json_decode($jsonString, true);
            // Update Key
            $data['access_token'] = $access_token;
            // Write File
            $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents(base_path('storage/' . self::FILE_NAME), stripslashes($newJsonString));

        } catch (\Exception $e) {
            Log::Error('Something went wrong: ' . $e->getMessage());
        }

    }

}
