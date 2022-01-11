<?php
/**
 * @author        Asim Sarwar
 * Date           09-12-2021
 * Class          BrightcoveAPISettingResource
 */
namespace App\Http\Resources\V1\Integration;

use Illuminate\Http\Resources\Json\JsonResource;

class BrightcoveAPISettingResource extends JsonResource
{
    /**
     * @author        Asim Sarwar
     * Date           09-12-2021
     * Transform the resource into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
