<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\ExampleMethodController;
use Styde\Enlighten\Http\Controllers\ExampleGroupController;
use Styde\Enlighten\Http\Controllers\RunController;
use Styde\Enlighten\Http\Controllers\WelcomeController;

Route::prefix('enlighten')->middleware('web')->group(function () {

    Route::get('/intro', WelcomeController::class)
            ->name('enlighten.intro');

    Route::get('/', [RunController::class, 'index'])
            ->name('enlighten.run.index');

    Route::get('/modules/{area?}', [RunController::class, 'show'])
            ->name('enlighten.run.show');

    Route::get('/{group:slug}', [ExampleGroupController::class, 'show'])
        ->name('enlighten.group.show');

    Route::get('/{group:slug}/{method}', [ExampleMethodController::class, 'show'])
        ->name('enlighten.method.show');
});
