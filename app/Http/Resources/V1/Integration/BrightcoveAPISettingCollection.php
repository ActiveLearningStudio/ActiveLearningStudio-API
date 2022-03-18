<?php
/**
 * @author        Asim Sarwar
 * Date           09-12-2021
 * Class          BrightcoveAPISettingCollection
 */
namespace App\Http\Resources\V1\Integration;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrightcoveAPISettingCollection extends ResourceCollection
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
