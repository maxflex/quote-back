<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return new AuthResource(auth()->user());
    }
}
