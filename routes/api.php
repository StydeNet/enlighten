<?php

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Http\Controllers\WidgetController;
use Styde\Enlighten\Http\Controllers\SearchController;

Route::prefix('enlighten/api')->group(function () {
    Route::get('run/{run}/search', [SearchController::class, 'index'])->name('enlighten.api.search');
});

Route::prefix('/enlighten/widget')->group(function () {
    Route::get('/{widget}', WidgetController::class)->name('enlighten.widget');
});


// enlighten/runs-table
