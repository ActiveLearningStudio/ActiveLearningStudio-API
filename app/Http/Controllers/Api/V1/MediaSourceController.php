<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MediaSource\StoreMediaSource;
use App\Http\Requests\V1\MediaSource\UpdateMediaSource;
use App\Http\Resources\V1\MediaSource\MediaSourceCollection;
use App\Http\Resources\V1\MediaSource\MediaSourceResource;
use App\Repositories\MediaSources\MediaSourcesInterface;
use Illuminate\Http\Request;

/**
 * @authenticated
 *
 * @group 1012.   Admin/MediaSource 
 * APIs for MediaSource on admin panel.
 */
class MediaSourceController extends Controller
{
    private $mediaSourceRepository;

    /**
     * MediaSourceController constructor.
     * @param mediaSourceRepository $mediaSourceRepository
     */
    public function __construct(MediaSourcesInterface $mediaSourceRepository)
    {
        $this->mediaSourceRepository = $mediaSourceRepository;
    }

    /**
     * Get All Media Source for listing.
     * 
     * @bodyParam query string Query to search media sources settings against name or media type Example: Kaltura or Video/Image
     * @bodyParam size integer Size to show per page records Example: 10
     * @bodyParam order_by_column string To sort data with specific column Example: name
     * @bodyParam order_by_type string To sort data in ascending or descending order Example: asc
     * 
     * @responseFile responses/admin/mediasources/media-source-settings-list.json
     * 
     * @param Request $request 
     * @return MediaSourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->mediaSourceRepository->getAll($request->all());
        return new MediaSourceCollection($collections);
    }

    /**
     * Get Media Source
     *
     * Get the specified media source details.
     *     
     * @urlParam id required The Id of a media source Example: 1
     *
     * @responseFile responses/admin/mediasources/media-source-settings-show.json
     *
     * @param $id
     * @return MediaSourceResource
     */
    public function show($id)
    {
        $setting = $this->mediaSourceRepository->find($id);
        return new MediaSourceResource($setting);
    }

    /**
     * Create Media Source
     * 
     * Creates the new media source in database
     * 
     * @bodyParam name string required Media Source name Example: Kaltura
     * @bodyParam media_type string required Media Source Type Example: Video/Image
     *
     * @responseFile 200 responses/admin/mediasources/media-source-settings-create.json
     * 
     * @param StoreMediaSource $request
     * @return MediaSourceResource
     */
    public function store(StoreMediaSource $request)
    {
        $data = $request->only([
            'name',
            'media_type'
        ]);
        $response = $this->mediaSourceRepository->create($data);
        return response(['message' => $response['message'], 'data' => new MediaSourceResource($response['data'])], 200);
    }

    /**
     * Update Media Source
     * 
     * Update the specific media source record
     * 
     * @bodyParam name string required Media Source name Example: Kaltura
     * @bodyParam media_type string required Media Source Type Example: Video/Image
     *
     * @responseFile 200 responses/admin/mediasources/media-source-settings-update.json
     * 
     * @param UpdateMediaSource $request
     * @param $id 
     * @return MediaSourceResource
     */
    public function update(UpdateMediaSource $request, $id)
    {
        $data = $request->only([
            'name',
            'media_type'
        ]);
        $response = $this->mediaSourceRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new MediaSourceResource($response['data'])], 200);
    }

    /**
     * Delete Media Source
     * 
     * Delete the specific media source record
     * 
     * @urlParam id required The Id of a Media Source Example: 1
     * 
     * @param $id
     * 
     * @responseFile 200 responses/admin/mediasources/media-source-settings-destory.json
     */
    public function destroy($id)
    {
        return response(['message' => $this->mediaSourceRepository->destroy($id)], 200);
    }
}
