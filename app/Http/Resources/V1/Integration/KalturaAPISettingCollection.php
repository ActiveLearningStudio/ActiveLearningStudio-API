<?php
/**
 * @author        Asim Sarwar
 * Date           21-01-2022
 * Class          KalturaAPISettingCollection
 */
namespace App\Http\Resources\V1\Integration;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KalturaAPISettingCollection extends ResourceCollection
{
    /**     
     * Transform the resource collection into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
