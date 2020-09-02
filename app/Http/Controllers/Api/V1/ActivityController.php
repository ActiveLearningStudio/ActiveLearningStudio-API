<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Http\Resources\V1\ActivityResource;
use App\Http\Resources\V1\ActivityDetailResource;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class ActivityController extends Controller
{
    private $activityRepository;

    /**
     * ActivityController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository) {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Display a listing of the activity.
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activities' => ActivityResource::collection($this->activityRepository->all()),
        ], 200);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'playlist_id' => 'integer',
            'order' => 'integer',
            'h5p_content_id' => 'integer',
            'thumb_url' => 'string',
            'subject_id' => 'string',
            'education_level_id' => 'string',
        ]);

        $activity = $this->activityRepository->create($data);

        if ($activity) {
            return response([
                'activity' => new ActivityResource($activity),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified activity.
     *
     * @param Activity $activity
     * @return Response
     */
    public function show(Activity $activity)
    {        
        return response([
            'activity' => new ActivityResource($activity),
        ], 200);
    }

    /**
     * Update the specified activity in storage.
     *
     * @param Request $request
     * @param Activity $activity
     * @return Response
     */
    public function update(Request $request, Activity $activity)
    {
        $is_updated = $this->activityRepository->update($request->only([
            'playlist_id',
            'title',
            'type',
            'content',
            'shared',
            'order',
            'thumb_url',
            'subject_id',
            'education_level_id',
            'h5p_content_id',
        ]), $activity->id);

        if ($is_updated) {
            return response([
                'activity' => new ActivityResource($this->activityRepository->find($activity->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity.'],
        ], 500);
    }

    /**
     * Display the specified activity in detail.
     *
     * @param Activity $activity
     * @return Response
     */
    public function detail(Activity $activity)
    {        
        $data = ['h5p_parameters' =>  null,  'user_name' => null,  'user_id' => null];   
        
        if ( $activity->playlist->project->user ) {
            $data['user_name'] = $activity->playlist->project->user;
            $data['user_id'] = $activity->playlist->project->id;
        }

        if ( $activity->type === 'h5p' ) {
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;		
            $content = $h5p->load_content($activity->h5p_content_id);		
            $library = $content['library'] ? \H5PCore::libraryToString($content['library']) : 0;				
            $data['h5p_parameters'] = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        }
        
        return response([
            'activity' => new ActivityDetailResource($activity, $data),
        ], 200);
    }

    /**
     * Remove the specified activity from storage.
     *
     * @param Activity $activity
     * @return Response
     */
    public function destroy(Activity $activity)
    {
        $is_deleted = $this->activityRepository->delete($activity->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity.'],
        ], 500);
    }
    
    public function clone( Playlist $playlist,Activity $activity)
    {
        if ($activity->is_public) {
            return response([
                'errors' => ['Not a Public PlayList.'],
                    ], 500);
        }
        $activity_data = [
                    'title' => $activity->title,
                    'type' => $activity->type,
                    'content' => $activity->content,
                    'playlist_id' => $playlist->id,
                    'order' => $activity->order,
                    'h5p_content_id' => $activity->h5p_content_id,
                    'thumb_url' => $activity->thumb_url,
                    'subject_id' => $activity->subject_id,
                    'education_level_id' => $activity->education_level_id,
                ];

        $cloned_activity = $this->activityRepository->create($activity_data);
    }
}
