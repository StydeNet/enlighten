<?php

use Illuminate\Support\Facades\Route;
use Tests\App\Http\Resources\UserResource;
use Tests\App\Models\User;

Route::get('users/{status?}/{page?}', function () {
    $users = User::all();

    return UserResource::collection($users);
});
