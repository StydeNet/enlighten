<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\DashboardController;
use Styde\Enlighten\Http\Controllers\RunController;
use Styde\Enlighten\Http\Controllers\TestClassController;
use Styde\Enlighten\Http\Controllers\WelcomeController;

Route::prefix('enlighten')->middleware('web')->group(function () {
        Route::get('/intro', WelcomeController::class)
            ->name('enlighten.intro');

        Route::get('/', [DashboardController::class, 'index'])
            ->name('enlighten.run.index');

        Route::get('/run/{run?}/{area?}', [RunController::class, 'show'])
            ->name('enlighten.run.show');

        Route::get('run/{run}/{area}/{group:slug}', [TestClassController::class, 'show'])
            ->name('enlighten.group.show');
    });
