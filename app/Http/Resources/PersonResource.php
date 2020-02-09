<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'realname', 'nickname'
        ], [
            'photo' => new PhotoResource($this->photo),
        ]);
    }
}
