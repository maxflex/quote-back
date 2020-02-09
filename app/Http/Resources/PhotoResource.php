<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return extract_fields($this, [
            'id', 'url', 'url_version', 'entity_type', 'entity_id'
        ]);
    }
}
