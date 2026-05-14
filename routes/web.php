<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/asset-status', [DashboardController::class, 'assetStatus']);
Route::post('/publish-build', [DashboardController::class, 'publishBuild']);
Route::post('/create-backup', [DashboardController::class, 'createBackup']);
Route::post('/clear-assets', [DashboardController::class, 'clearAssets']);
Route::delete('/delete-build/{id}', [DashboardController::class, 'deleteBuild']);