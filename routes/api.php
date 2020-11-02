<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\SearchController;

Route::prefix('enlighten/api')
    ->middleware(SubstituteBindings::class)
    ->group(function () {
        Route::get('run/{run}/search', SearchController::class)->name('enlighten.api.search');
    });
