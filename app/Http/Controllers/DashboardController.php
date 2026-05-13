<?php

namespace App\Http\Controllers;

use App\Models\BuildHistory;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $builds = BuildHistory::latest()->get();

        $totalBuilds = BuildHistory::count();

        $latestBuild = BuildHistory::latest()->first();

        $assetFiles = 0;

        if (File::exists(public_path('build'))) {
            $assetFiles = count(File::allFiles(public_path('build')));
        }

        return view('dashboard', compact(
            'builds',
            'totalBuilds',
            'latestBuild',
            'assetFiles'
        ));
    }

    public function assetStatus()
    {
        $jsExists = File::exists(public_path('build/assets'));
        $storageLinked = File::exists(public_path('storage'));

        $cssExists = false;

        if ($jsExists) {
            $files = File::allFiles(public_path('build/assets'));

            foreach ($files as $file) {
                if (str_contains($file->getFilename(), '.css')) {
                    $cssExists = true;
                }
            }
        }

        return view('asset-status', compact(
            'jsExists',
            'cssExists',
            'storageLinked'
        ));
    }

    public function publishBuild()
    {
        $version = 'v' . rand(1, 99) . '.' . rand(0, 9);

        $assetCount = 0;

        if (File::exists(public_path('build'))) {
            $assetCount = count(File::allFiles(public_path('build')));
        }

        BuildHistory::create([
            'version' => $version,
            'asset_count' => $assetCount,
            'status' => 'Success',
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Build Published Successfully 🚀');
    }
}