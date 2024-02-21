<?php

namespace App\Http\Controllers\Api\V1\C2E\Publisher;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\C2E\Publisher\StorePublisherRequest;
use App\Http\Requests\V1\C2E\Publisher\UpdatePublisherRequest;
use App\Http\Requests\V1\C2E\Publisher\IndependentActivityPublishRequest;
use App\Http\Resources\V1\C2E\Publisher\PublisherCollection;
use App\Http\Resources\V1\C2E\Publisher\PublisherResource;
use App\Models\Organization;
use App\Models\IndependentActivity;
use App\Models\C2E\Publisher\Publisher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Repositories\C2E\Publisher\PublisherRepositoryInterface;
use App\Jobs\C2E\Publisher\PublishIndependentActivityToStore;

/**
 * @group 31. Publisher Settings
 *
 * APIs for publisher.
 */
class PublisherController extends Controller
{
    private $publisherRepository;

    /**
     * PublisherController constructor.
     * 
     * @param PublisherRepositoryInterface $publisherRepository
     */
    public function __construct(PublisherRepositoryInterface $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Get All Publisher Settings for listing.
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/c2e/publisher/publishers.json
     *
     * @param Request $request
     * 
     * @return PublisherCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [Publisher::class, $suborganization]);

        $collections = $this->publisherRepository->getAll($request->all(), $suborganization);
        return response(new PublisherCollection($collections), 200);
    }

    /**
     * Get Publisher Setting
     *
     * Get the specified publisher setting data.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam setting required The Id of a publisher setting Example: 1
     *
     * @responseFile responses/c2e/publisher/publisher.json
     *
     * @param Organization $suborganization
     * @param Publisher $setting
     * @return PublisherResource
     * @throws GeneralException
     */
    public function show(Organization $suborganization, Publisher $setting)
    {
        $this->authorize('view', $setting);

        return response(new PublisherResource($setting->load('user', 'organization')), 200);
    }

    /**
     * Create Publisher Setting
     *
     * Creates the new publisher setting in database.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Publisher name Example: PublisherName
     * @bodyParam description string Publisher description Example: Publisherdescription
     * @bodyParam url string required Publisher url Example: https://cee-publisher-service.curriki.org/
     * @bodyParam key string required Publisher key Example: oQ/iXdY0gaBFo99Fh0mA7xknHlhyNb/W8cQuAhqGjKk=
     * @bodyParam project_visibility boolean Publisher project visibility Example: true
     * @bodyParam playlist_visibility string Publisher playlist visibility Example: false
     * @bodyParam activity_visibility string Publisher activity visibility Example: true
     * 
     * @responseFile responses/c2e/publisher/publisher-create.json
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }
     *
     * @param StorePublisherRequest $request
     * @param Organization $suborganization
     * @return PublisherResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StorePublisherRequest $request, Organization $suborganization)
    {
        $this->authorize('create', [Publisher::class, $suborganization]);

        $validated = $request->validated();
        $authUser = auth()->user();
        $validated['user_id'] = $authUser->id;
        $validated['organization_id'] = $suborganization->id;
        $response = $this->publisherRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new PublisherResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Update Publisher Setting
     *
     * Updates the publisher setting in database.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam setting required The Id of a publisher setting Example: 1
     * @bodyParam name string required Publisher name Example: PublisherName
     * @bodyParam description string Publisher description Example: Publisherdescription
     * @bodyParam url string required Publisher url Example: https://cee-publisher-service.curriki.org/
     * @bodyParam key string required Publisher key Example: oQ/iXdY0gaBFo99Fh0mA7xknHlhyNb/W8cQuAhqGjKk=
     * @bodyParam project_visibility boolean Publisher project visibility Example: true
     * @bodyParam playlist_visibility string Publisher playlist visibility Example: false
     * @bodyParam activity_visibility string Publisher activity visibility Example: true
     *
     * @responseFile responses/c2e/publisher/publisher-update.json
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update publisher setting, please try again later."
     *   ]
     * }
     *
     * @param UpdatePublisherRequest $request
     * @param Organization $suborganization
     * @param Publisher $setting
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(UpdatePublisherRequest $request, Organization $suborganization, Publisher $setting)
    {
        $this->authorize('update', $setting);

        $validated = $request->validated();
        $response = $this->publisherRepository->update($setting, $validated);
        return response(['message' => $response['message'], 'data' => new PublisherResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Delete Publisher Setting
     *
     * Deletes the publisher setting from database.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam setting required The Id of a publisher setting Example: 1
     *
     * @response {
     *   "message": "Publisher setting deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete publisher setting, please try again later."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param Publisher $setting
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy(Organization $suborganization, Publisher $setting)
    {
        $this->authorize('delete', $setting);

        return response(['message' => $this->publisherRepository->destroy($setting)], 200);
    }

    /**
     * Get Publisher Stores
     *
     * Display a listing of the stores for the publisher
     * 
     * @urlParam publisher required The Id of a publisher Example: 1
     *
     * @responseFile responses/c2e/publisher/stores.json
     * 
     * @param Publisher $publisher
     * @return Response
     */
    public function getStores(Publisher $publisher)
    {
        $this->authorize('viewAnyStore', $publisher);

        return $this->publisherRepository->getStores($publisher);
    }

    /**
     * Publish Independent Activity To Store
     *
     * Publish the specified independent activity to selected store of a user.
     *
     * @urlParam publisher required The Id of a publisher Example: 1
     * @urlParam independent_activity required The Id of a independent_activity Example: 1
     * @bodyParam store_id integer required The Id of store Example: 2
     *
     * @response {
     *   "message": "Independent activity published successfully!"
     * }
     *
     * @param IndependentActivityPublishRequest $request
     * @param Publisher $publisher
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function publish(IndependentActivityPublishRequest $request, Publisher $publisher, IndependentActivity $independent_activity)
    {
        $this->authorize('publishIndependentActivity', $publisher);

        $requestData = $request->validated();

        // pushed cloning of activity in background
        // PublishIndependentActivityToStore::dispatch(auth()->user(), $publisher, $independent_activity, $requestData['store_id'])->delay(now()->addSecond());

        $message = 'Failed to publish independent activity!';

        try {
            $response = $this->publisherRepository
                                ->publishIndependentActivity(
                                    auth()->user(),
                                    $publisher,
                                    $independent_activity,
                                    $requestData['store_id']
                                );

            if (isset($response['message']) && $response['message'] === 'success') {
                $message = 'Independent activity published successfully!';
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return response([
            'message' =>  $message,
        ], 200);
    }

    /**
     * Get independent activity publish media
     *
     * Display a listing of the media to be published for the specified independent activity
     *
     * @urlParam publisher required The Id of a publisher Example: 1
     * @urlParam independent_activity required The Id of a independent_activity Example: 1
     *
     * @responseFile responses/c2e/publisher/publish-media.json
     *
     * @param Publisher $publisher
     * @param IndependentActivity $independent_activity
     * @return Response
     */
    public function getPublishMedia(Publisher $publisher, IndependentActivity $independent_activity)
    {
        $this->authorize('publishIndependentActivity', $publisher);

        return $this->publisherRepository->getPublishMedia($independent_activity);
    }
}
