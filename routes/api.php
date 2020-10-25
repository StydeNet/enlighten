<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\SearchController;
use Styde\Enlighten\Http\Controllers\WidgetController;

Route::prefix('enlighten/api')->group(function () {
    Route::get('run/{run}/search', [SearchController::class, 'index'])->name('enlighten.api.search');
});
