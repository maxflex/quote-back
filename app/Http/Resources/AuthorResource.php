<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'id', 'nickname', 'realname'
        ], [
            'quotes_count' => $this->quotes()->count(),
            'likes_count' => $this->quotes->sum('likes_count'),
            'photo' => new PhotoResource($this->photo)
        ]);
    }
}
