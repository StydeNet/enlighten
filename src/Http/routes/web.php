<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\ListRunsController;
use Styde\Enlighten\Http\Controllers\SearchController;
use Styde\Enlighten\Http\Controllers\ShowAreaController;
use Styde\Enlighten\Http\Controllers\ShowExampleController;
use Styde\Enlighten\Http\Controllers\ShowExampleGroupController;
use Styde\Enlighten\Http\Controllers\WelcomeController;

Route::prefix('enlighten')->middleware('web')->group(function () {
    Route::get('intro', WelcomeController::class)
        ->name('enlighten.intro');

    Route::get('/', ListRunsController::class)
        ->name('enlighten.run.index');

    Route::get('run/{run}/areas/{area?}', ShowAreaController::class)
        ->name('enlighten.area.show');

    Route::get('run/{run}/{group:slug}', ShowExampleGroupController::class)
        ->name('enlighten.group.show');

    Route::get('run/{run}/{group:slug}/{example:slug}', ShowExampleController::class)
        ->name('enlighten.method.show');
});

Route::prefix('enlighten/api')
    ->middleware(SubstituteBindings::class)
    ->group(function () {
        Route::get('run/{run}/search', SearchController::class)->name('enlighten.api.search');
    });
