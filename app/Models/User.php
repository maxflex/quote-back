<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasPhoto;

    protected $fillable = ['nickname', 'realname', 'password', 'email'];
    protected $hidden = ['password'];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function likes()
    {
        return $this->hasMany(QuoteLike::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $this->encodePassword($value);
    }

    public function encodePassword(string $password): string
    {
        return Hash::make($password);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(fn ($user) => $user->api_token = Str::random(80));
    }
}
