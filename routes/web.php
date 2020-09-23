<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\EnlightenController;

Route::prefix('enlighten')->middleware('web')->group(function () {
    Route::get('/dashboard', [EnlightenController::class, 'index'])
        ->name('enlighten.dashboard');

    Route::get('/example/{example}', [EnlightenController::class, 'show'])
        ->name('enlighten.example.show');
});
