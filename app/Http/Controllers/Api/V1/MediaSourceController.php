<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MediaSource\SearchMediaSourceRequest;
use App\Http\Requests\V1\MediaSource\StoreMediaSourceRequest;
use App\Http\Requests\V1\MediaSource\UpdateMediaSourceRequest;
use App\Http\Resources\V1\MediaSource\MediaSourceCollection;
use App\Http\Resources\V1\MediaSource\MediaSourceResource;
use App\Repositories\MediaSources\MediaSourcesInterface;

/**
 * @authenticated
 *
 * @group 31.   Admin/MediaSource 
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
     * @urlParam query string Query to search media sources settings against name or media type Example: Kaltura or Video/Image
     * @urlParam size integer Size to show per page records Example: 10
     * @urlParam order_by_column string To sort data with specific column Example: name
     * @urlParam order_by_type string To sort data in ascending or descending order Example: asc
     * @urlParam filter string to search media sources by Image or Video Example: Video/Image
     * 
     * @responseFile responses/admin/mediasources/media-source-settings-list.json
     * 
     * @param SearchMediaSourceRequest $request
     * @return MediaSourceCollection
     */
    public function index(SearchMediaSourceRequest $request)
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
     * @response 500 {
     *   "errors": [
     *     "Media Source not found!"
     *   ]
     * }
     * 
     * @param $id int
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
     * @response 500 {
     *   "errors": [
     *     "Unable to create media source, please try again later!"
     *   ]
     * } 
     * 
     * @param StoreMediaSourceRequest $request
     * @return MediaSourceResource
     */
    public function store(StoreMediaSourceRequest $request)
    {
        $response = $this->mediaSourceRepository->create($request->all());
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
     * @response 500 {
     *   "errors": [
     *     "Unable to update media source, please try again later!"
     *   ]
     * } 
     * 
     * @param UpdateMediaSourceRequest $request
     * @param $id int
     * @return MediaSourceResource
     */
    public function update(UpdateMediaSourceRequest $request, $id)
    {
        $response = $this->mediaSourceRepository->update($id, $request->all());
        return response(['message' => $response['message'], 'data' => new MediaSourceResource($response['data'])], 200);
    }

    /**
     * Delete Media Source
     * 
     * Delete the specific media source record
     * 
     * @urlParam id required The Id of a Media Source Example: 1
     * 
     * @param $id int
     * 
     * @responseFile 200 responses/admin/mediasources/media-source-settings-destory.json
     * 
     * @response 500 {
     *   "errors": [
     *     "Unable to delete media source, please try again later!"
     *   ]
     * } 
     */
    public function destroy($id)
    {
        return response(['message' => $this->mediaSourceRepository->destroy($id)], 200);
    }
}
