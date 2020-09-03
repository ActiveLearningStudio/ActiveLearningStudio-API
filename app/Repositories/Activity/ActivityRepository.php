<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Models\Playlist;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface {

    private $client;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     */
    public function __construct(Activity $model) 
    {
        $this->client = new \GuzzleHttp\Client();
        parent::__construct($model);
    }

    public function clone(Request $request, Playlist $playlist, Activity $activity) 
    {
        $h5P_res = Null;
        $token = $request->bearerToken();
        if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) {
            $h5P_res = $this->download_and_upload_h5p($token, $activity->h5p_content_id);
        }


        $new_thumb_url = config('app.default_thumb_url');
        if (Storage::disk('public')->exists('projects/' . basename($activity->thumb_url)) && is_file(storage_path("app/public/projects/" . basename($activity->thumb_url)))) {
            $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
            $new_image_name_mtd = uniqid() . '.' . $ext;
            ob_start();
            \File::copy(storage_path("app/public/projects/" . basename($activity->thumb_url)), storage_path("app/public/projects/" . $new_image_name_mtd));
            ob_get_clean();
            $new_thumb_url = "/storage/projects/" . $new_image_name_mtd;
        }
        $activity_data = [
            'title' => $activity->title,
            'type' => $activity->type,
            'content' => $activity->content,
            'playlist_id' => $playlist->id,
            'order' => $activity->order,
            'h5p_content_id' => $activity->h5p_content_id,
            'thumb_url' => $new_thumb_url,
            'subject_id' => $activity->subject_id,
            'education_level_id' => $activity->education_level_id,
        ];

        $cloned_activity = $this->activityRepository->create($activity_data);

        return $cloned_activity['id'];
    }

    public function download_and_upload_h5p($token, $h5p_content_id) 
    {
        $new_h5p_file = uniqid() . ".h5p";

        $response = null;
        try {
            $response = $this->client->request('GET', config('app.url') . '/api/h5p/export/' . $h5p_content_id, ['sink' => storage_path("app/public/uploads/" . $new_h5p_file), 'http_errors' => false]);
        } catch (Exception $ex) {
            
        }

        if (!is_null($response) && $response->getStatusCode() == 200) {
            $response_h5p = null;
            try { 
                $response_h5p = $this->client->request('POST', config('app.url') . '/api/v1/h5p', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'multipart' => [
                        [
                            'name' => 'action',
                            'contents' => 'upload'
                        ],
                        [
                            'name' => 'h5p_file',
                            'contents' => fopen(storage_path("app/public/uploads/" . $new_h5p_file), 'r')
                        ]
                    ]
                ]); 
            } catch (Excecption $ex) {
                
            }
            
            if (!is_null($response_h5p) && $response_h5p->getStatusCode() == 200) {
                unlink(storage_path("app/public/uploads/" . $new_h5p_file));
                return json_decode($response_h5p->getBody()->getContents());
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

}
