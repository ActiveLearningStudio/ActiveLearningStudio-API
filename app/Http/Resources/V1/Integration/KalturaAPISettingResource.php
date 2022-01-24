<?php
/**
 * @author        Asim Sarwar
 * Date           21-01-2022
 * Class          KalturaAPISettingResource
 */
namespace App\Http\Resources\V1\Integration;

use Illuminate\Http\Resources\Json\JsonResource;

class KalturaAPISettingResource extends JsonResource
{
    /**
     * @author        Asim Sarwar
     * Date           21-01-2022
     * Transform the resource into an array.     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
