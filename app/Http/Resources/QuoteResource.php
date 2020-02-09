<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'text', 'likes_count'
        ], [
            'author' => new PersonResource($this->author),
            'is_liked' => $this->likes()->where('user_id', auth('api')->id())->exists(),
        ]);
    }
}
