<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $validationRules = [
        'email' => ['required', 'email', 'unique:users'],
        'nickname' => ['required', 'unique:users'],
        'password' => ['required_without:id', 'min:6', 'confirmed'],
    ];

    protected $filters = [
        'equals' => ['id']
    ];

    public function index(Request $request)
    {
        $query = User::query();

        $this->filter($request, $query);

        return $this->handleIndexRequest($request, $query, UserResource::class);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(User $user, Request $request)
    {
        $user->update($request->all());

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $this->handleValidation($request, [
            'password' => ['required', 'confirmed']
        ]);

        $user = User::create($request->all());

        return new AuthResource($user);
    }
}
