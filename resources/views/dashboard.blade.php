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
        .rollback-btn { cursor: pointer; }
        .log-modal { display: none; }
        .log-modal.active { display: flex; }
    </style>
</head>
<body class="p-6">
    <div class="max-w-6xl mx-auto">
        
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📦 Laravel Lasso</h1>
                    <p class="text-gray-500 text-sm mt-1">Asset Deployment Manager</p>
                </div>
                <div class="flex gap-3">
                    <a href="/asset-status" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm btn">🔍 Asset Status</a>
                    <a href="/" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm btn">🏠 Home</a>
                </div>
            </div>
        </div>
        
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
        
        <div class="grid md:grid-cols-4 gap-4 mb-6">
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
            <div class="bg-white rounded-lg shadow-sm p-5 border border-green-200 bg-green-50">
                <div class="text-gray-500 text-sm">Active Build</div>
                <div class="text-2xl font-bold text-green-600">{{ $activeBuild?->version ?? 'None' }}</div>
                <div class="text-xs text-gray-500">{{ $activeBuild?->published_at?->diffForHumans() ?? '' }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h2 class="font-semibold text-gray-800 mb-3">⚡ Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <form action="/publish-build" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm btn">📦 Publish New Build</button>
                </form>
                
                <form action="/create-backup" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-lg text-sm btn">💾 Create Backup</button>
                </form>
                
                <form action="/clear-assets" method="POST" class="inline" onsubmit="return confirm('⚠️ Delete all assets? This cannot be undone!')">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg text-sm btn">🧹 Clear Assets</button>
                </form>

                <form action="/cleanup-old-builds" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-500 text-white px-5 py-2 rounded-lg text-sm btn" onclick="return confirm('🧹 Delete old builds? Keep only last 10')">
                        🧹 Cleanup Old Builds
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">📋 Build History</h2>
                <span class="text-xs text-gray-500">{{ $builds->count() }} builds</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Version</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assets</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($builds as $build)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $build->version }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $build->asset_count }} files</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $build->status == 'Success' || $build->status == 'Rollback' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $build->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($build->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800">✅ Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600">❌ Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $build->published_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <button onclick="viewLog({{ $build->id }})" 
                                            class="text-blue-600 hover:text-blue-800 text-sm">📄 Log</button>
                                        
                                        @if(!$build->is_active)
                                            <form action="/rollback-build/{{ $build->id }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm rollback-btn" 
                                                    onclick="return confirm('🔄 Rollback to version {{ $build->version }}?')">
                                                    🔄 Rollback
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="/delete-build/{{ $build->id }}" method="POST" class="inline" 
                                            onsubmit="return confirm('Delete build {{ $build->version }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No builds found. Click "Publish New Build" to start!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- Log Modal -->
    <div id="logModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[80vh] flex flex-col">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">📄 Build Log</h3>
                <button onclick="closeLog()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div id="logContent" class="p-6 overflow-y-auto flex-1 bg-gray-900 text-green-400 font-mono text-sm rounded-b-lg whitespace-pre-wrap">
                Loading...
            </div>
        </div>
    </div>

    <script>
        function viewLog(id) {
            const modal = document.getElementById('logModal');
            const content = document.getElementById('logContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.textContent = 'Loading...';
            
            fetch('/build-log/' + id)
                .then(response => response.text())
                .then(data => {
                    content.textContent = data;
                    content.scrollTop = content.scrollHeight;
                })
                .catch(() => {
                    content.textContent = '❌ Error loading log';
                });
        }

        function closeLog() {
            const modal = document.getElementById('logModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('click', function(e) {
            if (e.target === document.getElementById('logModal')) {
                closeLog();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLog();
            }
        });
    </script>
</body>
</html>