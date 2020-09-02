<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
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
    
    
    public function download_and_upload_h5p($h5p_content_id)
    {  
        
        $new_h5p_file = uniqid().".h5p";
        $response = null;
        try {
            $response = $this->client->request('GET', config('constants.h5p-api-url').'/h5p/export/'.$h5p_content_id , ['sink' => storage_path("app/public/uploads/".$new_h5p_file) , 'http_errors' => false]);    
        } catch (Exception $ex) {
            
        }
        
        if(!is_null($response) && $response->getStatusCode() == 200){            
            $response_h5p = null;
            try {
                $response_h5p = $this->client->request('POST', config('constants.h5p-api-url').'/api/h5p' , [
                    'multipart' => [
                        [
                            'name'     => 'action',
                            'contents' => 'upload'
                        ],
                        [
                            'name'     => 'h5p_file',
                            'contents' => fopen(storage_path("app/public/uploads/".$new_h5p_file), 'r')
                        ]
                    ]
                ]);
            } catch (Excecption $ex) {
                
            }            
            
            if(!is_null($response_h5p) && $response_h5p->getStatusCode() == 200){
                unlink(storage_path("app/public/uploads/".$new_h5p_file));
                return json_decode($response_h5p->getBody()->getContents());
            }else {
                return null;
            }

        }else {
            return null;
        }
    }
    
    
}
