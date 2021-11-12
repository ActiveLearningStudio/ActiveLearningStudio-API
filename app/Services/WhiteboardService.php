<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Services\WhiteboardInterface;
use Illuminate\Support\Facades\Http;

/**
 * Get Whiteboard URL Service class
 */
class WhiteboardService implements WhiteboardInterface
{
    public $apiURL;
    public $tokenURL;
    public $headers;
    public $response;

    /**
     * WhiteboardService constructor.
     */
    public function __construct()
    {
        $this->apiURL = config('whiteboard.apiURL');
        $this->tokenURL = config('whiteboard.tokenURL');
        $this->headers = ['Accept' => 'application/json'];
        $this->response = [];
    }

    /**
     * Get Whiteboard URL Service class
     *
     * @param array $params
     * @param string $access_token
     */
    public function getWhiteboardURL($params, $access_token)
    {
        return $this->response = Http::withToken($access_token)
                              ->withHeaders($this->headers)
                              ->post($this->apiURL . 'whiteboardUrl/getWhiteboardUrl', $params);

        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException('Unauthorized'));
    }

    /**
     * Regenerate Access Token
     *
     */
    public function regenerateAccessToken()
    {
        $params['clientId'] = config('whiteboard.clientId');
        $params['secretKey'] = config('whiteboard.secretKey');

        $this->response = Http::withHeaders($this->headers)
                              ->post($this->tokenURL . 'client/get_token', $params);

        throw_if($this->response->failed()
                || $this->response->serverError(),
                new GeneralException('Unauthorized'));

        return $this->response->json()['data'];
    }

}
