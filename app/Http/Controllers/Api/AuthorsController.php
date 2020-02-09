<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    protected $validationRules = [
        'nickname' => ['required']
    ];

    public function index(Request $request)
    {
        $query = Author::orderByRaw('nickname, realname');

        return $this->handleIndexRequest($request, $query, AuthorResource::class);
    }


    public function show(Author $author)
    {
        return new AuthorResource($author);
    }

    public function update(Request $request, Author $author)
    {
        $this->handleValidation($request);
        $author->update($request->all());
        return new AuthorResource($author);
    }

    public function store(Request $request)
    {
        $this->handleValidation($request);
        $author = Author::create($request->all());
        return new AuthorResource($author);
    }
}
