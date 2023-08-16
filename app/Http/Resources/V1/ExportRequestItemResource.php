<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportRequestItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = false;
        if ($this->item_type == 'USER') {
            $user = $this->exportRequest->organization->users->find($this->item_id);
        }

        $data = [
            'id' => $this->id,
            'export_request_id' => $this->whenNotNull($this->export_request_id),
            'parent_id' => $this->whenNotNull($this->parent_id),
            'item_id' => $this->item_id,
            'item_type' => $this->item_type,
            'exported_file_path' => $this->whenNotNull($this->exported_file_path),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'export_request_sub_items' => ExportRequestItemResource::collection($this->whenLoaded('subExportRequestsItems')),
            'user' => $this->when($user, new UserExportResource($user))

        ];

        return $data;
    }
}
