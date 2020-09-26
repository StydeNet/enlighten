<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\EnlightenController;

Route::prefix('enlighten')
    ->middleware('web')
    ->group(function () {
        Route::get('/dashboard/{suite?}', [EnlightenController::class, 'index'])
            ->name('enlighten.dashboard');

        Route::get('/{suite}/{group}', [EnlightenController::class, 'show'])
            ->name('enlighten.group.show');

        Route::view('/intro', 'enlighten::intro')->name('enlighten.intro');
    });
