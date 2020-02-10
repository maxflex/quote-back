<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray($request)
    {
        $isLiked = $this->likes()
            ->when(
                auth('api')->check(),
                fn ($query) => $query->where('user_id', auth('api')->id()),
                fn ($query) => $query->where('ip', $request->ip()),
            )
            ->exists();

        return extract_fields($this, [
            'id', 'text', 'likes_count'
        ], [
            'author' => new PersonResource($this->author),
            'is_liked' => $isLiked,
        ]);
    }
}
