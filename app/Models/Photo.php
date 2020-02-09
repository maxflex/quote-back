<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Photo extends Model
{
    public $timestamps = false;

    protected $fillable = ['entity_id', 'entity_type'];

    const FOLDER = 'photos/';

    // в этом формате сохраняются все фотки
    const EXTENSION = 'jpg';

    public function getFilenameAttribute()
    {
        return $this->id . '.' . self::EXTENSION;
    }

    public function getPathAttribute()
    {
        return storage_path('app/public/' . self::FOLDER . $this->filename);
    }

    public function getUrlAttribute()
    {
        return config('app.url') . '/storage/' . self::FOLDER . $this->filename;
    }

    /**
     * Для force reload после изменения
     */
    public function getUrlVersionAttribute()
    {
        return $this->url . '?ver=' . Str::random(10);
    }
}
