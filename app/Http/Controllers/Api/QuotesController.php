<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    protected $validationRules = [
        'author_id' => ['required', 'exists:authors,id'],
        'text' => ['required', 'min:3'],
    ];

    protected $filters = [
        'equals' => ['author_id'],
        'order' => ['order'],
    ];

    public function index(Request $request)
    {
        $query = Quote::withCount('likes');

        $this->filter($request, $query);

        return $this->handleIndexRequest($request, $query, QuoteResource::class);
    }


    public function show(Quote $quote)
    {
        return new QuoteResource($quote);
    }

    public function update(Request $request, Quote $quote)
    {
        $this->handleValidation($request);
        $quote->update($request->all());
        return new QuoteResource($quote);
    }

    public function store(Request $request)
    {
        $this->handleValidation($request);
        if (auth('api')->check()) {
            $quote = auth('api')->user()->quotes()->create(array_merge($request->all(), [
                'is_moderated' => true,
            ]));
        } else {
            $quote = Quote::create($request->all());
        }
        return new QuoteResource($quote);
    }

    public function like(Quote $quote, Request $request)
    {
        if (auth('api')->check()) {
            $like = auth('api')->user()
                ->likes()
                ->where('quote_id', $quote->id)
                ->first();
            $params = [
                'user_id' => auth('api')->id(),
            ];
        } else {
            $like = $quote->likes()
                ->where('ip', $request->ip())
                ->first();
            $params = [
                'ip' => $request->ip()
            ];
        }

        if ($like !== null) {
            $like->delete();
        } else {
            $quote->likes()->create($params);
        }
    }

    protected function filterOrder($field, $value, &$query)
    {
        switch ($value) {
            case "popular":
                return $query->orderBy('likes_count',  'desc');
            default:
                return $query->latest();
        }
    }
}
