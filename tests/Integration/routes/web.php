<?php

use Tests\Integration\App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/user/{user}', [UserController::class, 'show'])
    ->name('user.show')
    ->where('user', '\d+');

Route::post('user', [UserController::class, 'store'])
    ->name('user.store');

Route::get('server-error', function () {
    abort(500);
});
