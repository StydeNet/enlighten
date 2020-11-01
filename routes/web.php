<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\AreaController;
use Styde\Enlighten\Http\Controllers\ExampleGroupController;
use Styde\Enlighten\Http\Controllers\ExampleMethodController;
use Styde\Enlighten\Http\Controllers\RunController;
use Styde\Enlighten\Http\Controllers\WelcomeController;

Route::prefix('enlighten')->middleware('web')->group(function () {

    Route::get('/intro', WelcomeController::class)->name('enlighten.intro');

    Route::get('/', RunController::class)->name('enlighten.run.index');

    Route::prefix('/run/{run?}/')->group(function () {

        Route::get('areas/{area?}', AreaController::class)->name('enlighten.area.show');

        Route::get('{group:slug}', ExampleGroupController::class)->name('enlighten.group.show');

        Route::get('{group:slug}/{method}', ExampleMethodController::class)->name('enlighten.method.show');
    });
});
