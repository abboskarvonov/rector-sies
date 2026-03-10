<?php

use App\Http\Controllers\AppealController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppealController::class, 'index'])->name('appeals.index');

Route::get('/appeal/create', [AppealController::class, 'create'])->name('appeals.create');

Route::post('/appeal', [AppealController::class, 'store'])
    ->name('appeals.store');

Route::get('/appeal/{code}', [AppealController::class, 'show'])->name('appeals.show');

Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/track', [TrackingController::class, 'track'])->name('tracking.track');
