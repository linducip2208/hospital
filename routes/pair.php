<?php

use App\Http\Controllers\PairController;
use Illuminate\Support\Facades\Route;

Route::prefix('__pair')->group(function () {
    Route::get('/', [PairController::class, 'show'])->middleware('throttle:30,1')->name('pair.show');
    Route::post('/', [PairController::class, 'activate'])->middleware('throttle:10,1')->name('pair.activate');
    Route::get('/success', [PairController::class, 'success'])->middleware('throttle:30,1')->name('pair.success');
});
