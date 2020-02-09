<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasPhoto;

    protected $fillable = ['realname', 'nickname'];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
