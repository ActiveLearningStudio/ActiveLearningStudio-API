<?php

/**
 * This File defines handlers for Brightcove.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\H5pContent\H5pContentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

/**
 *
 * APIs for Brightcove
 */
class BrightcoveController extends Controller
{
    /**
     * User repository object
     *
     * @var H5pContentRepositoryInterface
     */
    private $h5pContentRepository;

    /**
     * Instantiate a Brightcove repositories instance.
     *
     * @param H5pContentRepositoryInterface $h5pContentRepository
     */
    public function __construct(H5pContentRepositoryInterface $h5pContentRepository)
    {
        $this->h5pContentRepository = $h5pContentRepository;
    }

    /**
     * H5P H5P Resource Settings
     * 
     * Get H5P Resource Settings
     *
     * @urlParam accountId required For brightcove video Example: 1
     * @urlParam videoId required For brightcove video Example: 1
     * @urlParam dataPlayer required For brightcove video Example: 1
     * @urlParam dataEmbed required For brightcove video Example: 1
     * 
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @response 404 {
     *   "h5p": null
     * }
     * 
     * @param $accountId 
     * @param $videoId 
     * @param $dataPlayer 
     * @param $dataEmbed 
     *
     * @return Response
     */
    public function getH5pResourceSettings($accountId, $videoId, $dataPlayer, $dataEmbed)
    {
        $record = $this->h5pContentRepository->getBrightcoveVideo($accountId, $videoId, $dataPlayer, $dataEmbed);
        if ($record) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $settings = $h5p::get_editor($content = null, 'preview');
            $content = $h5p->load_content($record->id);
            $content['disable'] = config('laravel-h5p.h5p_preview_flag');
            $embed = $h5p->get_embed($content, $settings);
            $embed_code = $embed['embed'];
            $settings = $embed['settings'];
            $user_data = null;
            $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];
            return response(['h5p' => $h5p_data], 200);
        } else {
            return response(['h5p' => null], 404);
        }
    }

    /**
     * Get Brightcove H5P Resource Settings
     * 
     * Get H5P Resource Settings For Brightcove
     *
     * @urlParam videoId required For brightcove video Example: 1
     *
     * @responseFile responses/h5p/h5p-resource-settings-open.json
     *
     * @response 404 {
     *   "h5p": null
     * }
     * 
     * @param $videoId
     *
     * @return Response
     */
    public function getH5pBrightcoveResourceSettings($videoId)
    {
        $h5p_content = $this->h5pContentRepository->getH5pContentId($videoId);
        if ($h5p_content) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $settings = $h5p::get_editor();
            $content = $h5p->load_content($h5p_content['h5p_content_id']);
            $content['disable'] = config('laravel-h5p.h5p_preview_flag');
            $embed = $h5p->get_embed($content, $settings);
            $embed_code = $embed['embed'];
            $settings = $embed['settings'];
            $user_data = null;
            $h5p_data = ['settings' => $settings, 'user' => $user_data, 'embed_code' => $embed_code];
            return response(['h5p' => $h5p_data], 200);
        } else {
            return response(['h5p' => null], 404);
        }
    }
}
