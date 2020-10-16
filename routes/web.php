<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\RunController;
use Styde\Enlighten\Http\Controllers\TestClassController;
use Styde\Enlighten\Http\Controllers\WelcomeController;

Route::prefix('enlighten')->middleware('web')->group(function () {
        Route::get('/intro', [WelcomeController::class, 'intro'])
            ->name('enlighten.intro');

        Route::get('/', [RunController::class, 'index'])
            ->name('enlighten.run.index');

        Route::get('/run/{run?}/{area?}', [RunController::class, 'show'])
            ->name('enlighten.run.show');

        Route::get('run/{run}/{area}/{group}', [TestClassController::class, 'show'])
            ->name('enlighten.group.show');
    });
