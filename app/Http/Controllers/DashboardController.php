<?php

namespace App\Http\Controllers;

use App\Models\BuildHistory;
use App\Models\BuildLog;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function index()
    {
        $builds = BuildHistory::latest()->get();
        $totalBuilds = BuildHistory::count();
        $latestBuild = BuildHistory::latest()->first();
        $activeBuild = BuildHistory::where('is_active', true)->first();
        
        $assetFiles = 0;
        if (File::exists(public_path('build'))) {
            $assetFiles = count(File::allFiles(public_path('build')));
        }
        
        return view('dashboard', compact('builds', 'totalBuilds', 'latestBuild', 'assetFiles', 'activeBuild'));
    }

   public function assetStatus()
{
    $jsExists = File::exists(public_path('build/assets'));
    $cssExists = false;
    $jsCount = 0;
    $cssCount = 0;
    
    if ($jsExists) {
        $files = File::allFiles(public_path('build/assets'));
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (str_contains($filename, '.js')) $jsCount++;
            if (str_contains($filename, '.css')) {
                $cssExists = true;
                $cssCount++;
            }
        }
    }
    
    $storageLinked = File::exists(public_path('storage'));
    $latestBuild = BuildHistory::latest()->first();
    
    return view('asset-status', compact(
        'jsExists', 
        'cssExists', 
        'storageLinked', 
        'jsCount', 
        'cssCount',
        'latestBuild'
    ));
}
    public function publishBuild(Request $request)
    {
        try {
            $lastBuild = BuildHistory::latest()->first();
            $newNum = $lastBuild ? (int) str_replace('v', '', $lastBuild->version) + 1 : 1;
            $version = 'v' . $newNum;

            // Run build command and capture output
            $logOutput = $this->runBuildCommand();

            $assetCount = 0;
            if (File::exists(public_path('build'))) {
                $assetCount = count(File::allFiles(public_path('build')));
            }

            // Deactivate old builds
            BuildHistory::where('is_active', true)->update(['is_active' => false]);

            $build = BuildHistory::create([
                'version' => $version,
                'asset_count' => $assetCount,
                'status' => 'Success',
                'published_at' => now(),
                'environment' => 'production',
                'deployed_by' => auth()->user()->name ?? 'System',
                'notes' => $request->notes ?? 'Regular deployment',
                'is_active' => true,
                'build_log' => $logOutput,
            ]);

            // Store log in separate table
            BuildLog::create([
                'build_id' => $build->id,
                'log_content' => $logOutput,
                'log_type' => 'build'
            ]);

            return redirect()->back()->with('success', '✅ Build ' . $version . ' published successfully!');

        } catch (\Exception $e) {
            BuildHistory::create([
                'version' => 'v' . ($newNum ?? 1),
                'asset_count' => 0,
                'status' => 'Failed',
                'published_at' => now(),
                'notes' => 'Failed build: ' . $e->getMessage(),
                'is_active' => false,
                'build_log' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', '❌ Build failed: ' . $e->getMessage());
        }
    }

    private function runBuildCommand()
    {
        $output = [];
        $returnCode = 0;
        
        // Check if npm exists
        exec('npm -v', $npmOutput, $npmCode);
        
        if ($npmCode !== 0) {
            return "⚠️ npm is not installed or not in PATH.\nPlease install Node.js and npm first.";
        }

        // Run npm run build
        exec('npm run build 2>&1', $output, $returnCode);
        
        $log = "📦 Build started at: " . now() . "\n";
        $log .= "🔧 Command: npm run build\n";
        $log .= "📋 Output:\n" . implode("\n", $output) . "\n";
        $log .= "📊 Exit Code: " . ($returnCode === 0 ? '✅ Success' : '❌ Failed') . "\n";
        
        return $log;
    }

    public function rollbackBuild($id)
    {
        try {
            $build = BuildHistory::findOrFail($id);
            
            if ($build->is_active) {
                return redirect()->back()->with('error', '⚠️ This build is already active!');
            }

            // Check if backup exists
            if ($build->backup_path && Storage::disk('local')->exists($build->backup_path)) {
                // Restore from backup
                Storage::disk('local')->copy($build->backup_path, public_path('build'));
                $restoreMessage = 'Restored from backup: ' . $build->backup_path;
            } else {
                // If no backup, check if assets exist
                if (!File::exists(public_path('build'))) {
                    return redirect()->back()->with('error', '❌ No assets to rollback!');
                }
                $restoreMessage = 'Using existing assets';
            }

            // Deactivate current active build
            BuildHistory::where('is_active', true)->update(['is_active' => false]);

            // Activate selected build
            $build->update([
                'is_active' => true,
                'status' => 'Rollback',
                'notes' => 'Rollback to version ' . $build->version . ' - ' . $restoreMessage
            ]);

            // Log the rollback
            BuildLog::create([
                'build_id' => $build->id,
                'log_content' => "🔄 Rollback performed to version: {$build->version}\n📅 Date: " . now() . "\n📝 " . $restoreMessage,
                'log_type' => 'rollback'
            ]);

            return redirect()->back()->with('success', '🔄 Rollback to version ' . $build->version . ' successful!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Rollback failed: ' . $e->getMessage());
        }
    }

    public function viewBuildLog($id)
    {
        $build = BuildHistory::findOrFail($id);
        
        // Get log from build record
        $logContent = $build->build_log ?? 'No log available for this build.';
        
        // Check if there are detailed logs
        $detailedLog = BuildLog::where('build_id', $id)->first();
        if ($detailedLog) {
            $logContent = $detailedLog->log_content;
        }

        return view('build-log-modal', compact('build', 'logContent'));
    }

    public function deleteBuild($id)
    {
        $build = BuildHistory::findOrFail($id);
        
        if ($build->is_active) {
            return redirect()->back()->with('error', '❌ Cannot delete active build!');
        }

        // Delete backup if exists
        if ($build->backup_path && Storage::disk('local')->exists($build->backup_path)) {
            Storage::disk('local')->delete($build->backup_path);
        }

        $build->delete();

        return redirect()->back()->with('success', '🗑️ Build deleted successfully!');
    }

    public function clearAssets()
    {
        if (File::exists(public_path('build'))) {
            File::deleteDirectory(public_path('build'));
            return redirect()->back()->with('success', '🧹 All assets cleared successfully!');
        }
        return redirect()->back()->with('error', 'No assets found to clear!');
    }

    public function createBackup()
    {
        if (!File::exists(public_path('build'))) {
            return redirect()->back()->with('error', 'No assets to backup!');
        }

        $backupName = 'backup_' . date('Y-m-d_His');
        $backupPath = 'backups/' . $backupName;

        // Create backup directory
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        // Zip the build folder
        $zip = new \ZipArchive();
        $zipFile = storage_path('app/backups/' . $backupName . '.zip');
        
        if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
            $files = File::allFiles(public_path('build'));
            foreach ($files as $file) {
                $relativePath = 'build/' . $file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
            $zip->close();

            // Update latest build with backup path
            $latestBuild = BuildHistory::latest()->first();
            if ($latestBuild) {
                $latestBuild->update([
                    'backup_path' => 'backups/' . $backupName . '.zip'
                ]);
            }

            return redirect()->back()->with('success', '💾 Backup created: ' . $backupName . '.zip');
        }

        return redirect()->back()->with('error', '❌ Failed to create backup!');
    }

    public function cleanupOldBuilds()
    {
        try {
            // Keep only last 10 builds
            $buildsToKeep = 10;
            $oldBuilds = BuildHistory::where('is_active', false)
                ->orderBy('id', 'desc')
                ->skip($buildsToKeep)
                ->take(100)
                ->get();

            $deletedCount = 0;
            foreach ($oldBuilds as $build) {
                // Delete backup if exists
                if ($build->backup_path && Storage::disk('local')->exists($build->backup_path)) {
                    Storage::disk('local')->delete($build->backup_path);
                }
                $build->delete();
                $deletedCount++;
            }

            return redirect()->back()->with('success', '🧹 Cleaned up ' . $deletedCount . ' old builds!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Cleanup failed: ' . $e->getMessage());
        }
    }
}