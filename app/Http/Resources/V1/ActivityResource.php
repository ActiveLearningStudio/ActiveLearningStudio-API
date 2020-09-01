<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {        
        $h5p_parameters = $user_name = $user_id = null;
        
        if( $this->playlist->project->user ){
            $user_name = $this->playlist->project->user;
            $user_id = $this->playlist->project->id;
        }
        if( $this->type === 'h5p' ){
            $h5p = App::make('LaravelH5p');
            $core = $h5p::$core;
            $editor = $h5p::$h5peditor;		
            $content = $h5p->load_content($this->h5p_content_id);		
            $library = $content['library'] ? \H5PCore::libraryToString($content['library']) : 0;				
            $h5p_parameters = '{"params":' . $core->filterParameters($content) . ',"metadata":' . json_encode((object)$content['metadata']) . '}';
        }

        return [
            'id' => $this->id,
            'playlist' => $this->playlist,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'shared' => $this->shared,
            'order' => $this->order,
            'thumb_url' => $this->thumb_url,
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'h5p' => $h5p_parameters,
            'h5p_content' => $this->h5p_content,
            'library_name' => $this->h5p_content->library->name,
            'major_version' => $this->h5p_content->library->major_version,
            'minor_version' => $this->h5p_content->library->minor_version,
            'user_name' => $user_name,
            'user_id' => $user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
