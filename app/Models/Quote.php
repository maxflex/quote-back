<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['author_id', 'text'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function likes()
    {
        return $this->hasMany(QuoteLike::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('likes-count', fn ($query) => $query->withCount('likes'));
    }
}
