<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/asset-status', [DashboardController::class, 'assetStatus']);

Route::post('/publish-build', [DashboardController::class, 'publishBuild']);