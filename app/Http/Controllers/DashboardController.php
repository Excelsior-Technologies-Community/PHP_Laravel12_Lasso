<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\BuildHistory;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard home - shows all builds
    public function index()
    {
        $builds = BuildHistory::latest()->get();
        $totalBuilds = BuildHistory::count();
        $latestBuild = BuildHistory::latest()->first();
        
        // Count asset files
        $assetFiles = 0;
        if (File::exists(public_path('build'))) {
            $assetFiles = count(File::allFiles(public_path('build')));
        }
        
        return view('dashboard', compact('builds', 'totalBuilds', 'latestBuild', 'assetFiles'));
    }

    // Check asset health status
    public function assetStatus()
    {
        // Check if JS exists
        $jsExists = File::exists(public_path('build/assets'));
        
        // Check if CSS exists
        $cssExists = false;
        if ($jsExists) {
            $files = File::allFiles(public_path('build/assets'));
            foreach ($files as $file) {
                if (str_contains($file->getFilename(), '.css')) {
                    $cssExists = true;
                    break;
                }
            }
        }
        
        // Check storage link
        $storageLinked = File::exists(public_path('storage'));
        
        // Count files
        $jsCount = 0;
        $cssCount = 0;
        if ($jsExists) {
            $files = File::allFiles(public_path('build/assets'));
            foreach ($files as $file) {
                if (str_contains($file->getFilename(), '.js')) $jsCount++;
                if (str_contains($file->getFilename(), '.css')) $cssCount++;
            }
        }
        
        return view('asset-status', compact('jsExists', 'cssExists', 'storageLinked', 'jsCount', 'cssCount'));
    }

    // Publish new build
    public function publishBuild(Request $request)
    {
        // Generate version number
        $lastBuild = BuildHistory::latest()->first();
        if ($lastBuild) {
            $lastNum = (int) str_replace('v', '', $lastBuild->version);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }
        
        $version = 'v' . $newNum;
        
        // Count assets
        $assetCount = 0;
        if (File::exists(public_path('build'))) {
            $assetCount = count(File::allFiles(public_path('build')));
        }
        
        // Save to database
        BuildHistory::create([
            'version' => $version,
            'asset_count' => $assetCount,
            'status' => 'Success',
            'published_at' => now(),
        ]);
        
        return redirect()->back()->with('success', '✅ Build ' . $version . ' published successfully!');
    }
    
    // Delete specific build
    public function deleteBuild($id)
    {
        $build = BuildHistory::findOrFail($id);
        $build->delete();
        return redirect()->back()->with('success', '🗑️ Build deleted successfully!');
    }
    
    // Clear all assets
    public function clearAssets()
    {
        if (File::exists(public_path('build'))) {
            File::deleteDirectory(public_path('build'));
            return redirect()->back()->with('success', '🧹 All assets cleared successfully!');
        }
        return redirect()->back()->with('error', 'No assets found to clear!');
    }
    
    // Create backup of assets
    public function createBackup()
    {
        if (File::exists(public_path('build'))) {
            $backupName = 'backup_' . date('Y-m-d_His');
            $backupPath = storage_path('app/backups/' . $backupName);
            
            // Create backup directory
            if (!File::exists(storage_path('app/backups'))) {
                File::makeDirectory(storage_path('app/backups'), 0777, true);
            }
            
            // Copy assets to backup
            File::copyDirectory(public_path('build'), $backupPath);
            
            return redirect()->back()->with('success', '💾 Backup created: ' . $backupName);
        }
        return redirect()->back()->with('error', 'No assets to backup!');
    }
}