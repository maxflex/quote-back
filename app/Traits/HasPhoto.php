<?php

namespace App\Traits;

use App\Models\Photo;

trait HasPhoto
{
    public function photo()
    {
        return $this->morphOne(Photo::class, 'entity');
    }
}
