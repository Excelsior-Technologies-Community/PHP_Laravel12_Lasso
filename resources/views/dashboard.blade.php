{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Lasso - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f9fafb; }
        .btn { transition: all 0.2s ease; }
        .btn:hover { transform: scale(1.02); }
    </style>
</head>
<body class="p-6">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"> Laravel Lasso</h1>
                    <p class="text-gray-500 text-sm mt-1">Asset Deployment Manager</p>
                </div>
                <div class="flex gap-3">
                    <a href="/asset-status" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm btn"> Asset Status</a>
                    <a href="/" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm btn"> Home</a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Stats Cards -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-gray-500 text-sm">Total Builds</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalBuilds }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-gray-500 text-sm">Asset Files</div>
                <div class="text-2xl font-bold text-gray-800">{{ $assetFiles }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-gray-500 text-sm">Latest Version</div>
                <div class="text-2xl font-bold text-blue-600">{{ $latestBuild?->version ?? 'N/A' }}</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h2 class="font-semibold text-gray-800 mb-3">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <form action="/publish-build" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm btn">
                         Publish New Build
                    </button>
                </form>
                
                <form action="/create-backup" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-lg text-sm btn">
                         Create Backup
                    </button>
                </form>
                
                <form action="/clear-assets" method="POST" class="inline" onsubmit="return confirm(' Delete all assets? This cannot be undone!')">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg text-sm btn">
                         Clear Assets
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Build History Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="font-semibold text-gray-800"> Build History</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Version</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assets</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($builds as $build)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $build->version }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $build->asset_count }} files</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        {{ $build->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $build->published_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-3">
                                    <form action="/delete-build/{{ $build->id }}" method="POST" onsubmit="return confirm('Delete this build?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No builds found. Click "Publish New Build" to start!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</body>
</html>