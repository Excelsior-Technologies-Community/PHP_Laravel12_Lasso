<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/asset-status', [DashboardController::class, 'assetStatus'])->name('asset.status');
Route::post('/publish-build', [DashboardController::class, 'publishBuild'])->name('publish.build');
Route::post('/create-backup', [DashboardController::class, 'createBackup'])->name('create.backup');
Route::post('/clear-assets', [DashboardController::class, 'clearAssets'])->name('clear.assets');
Route::delete('/delete-build/{id}', [DashboardController::class, 'deleteBuild'])->name('delete.build');
Route::post('/rollback-build/{id}', [DashboardController::class, 'rollbackBuild'])->name('rollback.build');
Route::get('/build-log/{id}', [DashboardController::class, 'viewBuildLog'])->name('build.log');
Route::post('/cleanup-old-builds', [DashboardController::class, 'cleanupOldBuilds'])->name('cleanup.builds');