<?php

use Illuminate\Support\Facades\Route;
use Tests\Integration\App\Http\Resources\UserResource;
use Tests\Integration\App\Models\User;

Route::get('users/{status?}/{page?}', function () {
    $users = User::all();

    return UserResource::collection($users);
});

Route::get('text', function () {
    return 'This is just a string';
});

Route::get('json-string', function () {
    return response()->json('Unsubscription was successful', 200);
});
