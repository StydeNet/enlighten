<?php

use Illuminate\Support\Facades\Route;
use Tests\Integration\App\Http\Controllers\UserController;

Route::get('/user/{user}', [UserController::class, 'show'])
    ->name('user.show')
    ->where('user', '\d+');

Route::post('user', [UserController::class, 'store'])
    ->name('user.store');

Route::get('request/{num}', function ($num) {
    return "Request {$num}";
});

Route::get('server-error', function () {
    abort(500, 'Server error');
});

Route::get('redirect-1', function () {
    return redirect('redirect-2');
});

Route::get('redirect-2', function () {
    return redirect('redirect-3');
});

Route::get('redirect-3', function () {
    return 'Final Response';
});
