<?php

Route::namespace('Api')->group(function () {
    Route::post('auth/login', 'AuthController@login');

    Route::get("quotes/like/{quote}", "QuotesController@like");
    Route::apiResources([
        'quotes' => 'QuotesController',
        'users' => 'UsersController',
    ]);
    Route::apiResource('authors', 'AuthorsController')->only(['index', 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::get('auth', 'AuthController@index');
        Route::get('profile', 'ProfileController@index');
        Route::apiResource('authors', 'AuthorsController')->only(['store', 'update']);
        Route::apiResources([
            'photos' => 'PhotosController',
        ]);
    });
});
