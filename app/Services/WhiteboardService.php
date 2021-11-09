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
    /**
     * Public properties
     */
    public $apiURL = 'https://curriki-whiteboard-bk.flyerssoft.com';
    public $tokenURL = 'http://50.116.28.176:8100';
    public $apiV = '/v1';
    public $headers = ['Accept' => 'application/json'];
    public $response = [];

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
                              ->post($this->apiURL . $this->apiV . '/whiteboardUrl/getWhiteboardUrl', $params);

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
                              ->post($this->tokenURL . $this->apiV . '/client/get_token', $params);

        throw_if($this->response->failed()
                || $this->response->serverError(),
                new GeneralException('Unauthorized'));

        return $this->response->json()['data'];
    }

}
