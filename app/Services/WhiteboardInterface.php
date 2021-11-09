<?php

namespace App\Services;

/**
 * Interface for Get Whiteboard URL
 */
interface WhiteboardInterface
{
     /**
     * Get Whiteboard URL
     *
     * @param array $params
     * @param string $access_token
     */
    public function getWhiteboardURL($params, $access_token);

    /**
     * Regenerate Access Token
     */
    public function regenerateAccessToken();
}
