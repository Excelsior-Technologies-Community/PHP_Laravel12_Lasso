<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Status - Laravel Lasso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f9fafb; }
        .status-card { transition: all 0.2s ease; }
        .status-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .progress-bar { height: 6px; border-radius: 10px; overflow: hidden; }
        .animated { animation: pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .6; } }
    </style>
</head>
<body class="p-6">
    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">🔍 Asset Health Checker</h1>
                    <p class="text-gray-500 text-sm mt-1">Check if your assets are working properly</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm">
                        🔄 Refresh
                    </button>
                    <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">← Back</a>
                </div>
            </div>
        </div>
        
        <!-- Summary Stats -->
        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-gray-500 text-sm">Total Assets</div>
                <div class="text-2xl font-bold text-blue-600">{{ $jsCount + $cssCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-gray-500 text-sm">JS Files</div>
                <div class="text-2xl font-bold text-green-600">{{ $jsCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-gray-500 text-sm">CSS Files</div>
                <div class="text-2xl font-bold text-purple-600">{{ $cssCount }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-gray-500 text-sm">Overall Status</div>
                @if($jsExists && $cssExists)
                    <div class="text-2xl font-bold text-green-600">✅ Healthy</div>
                @else
                    <div class="text-2xl font-bold text-red-600">⚠️ Issues</div>
                @endif
            </div>
        </div>
        
        <!-- Status Cards -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            
            <!-- JS Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card border-l-4 {{ $jsExists ? 'border-green-500' : 'border-red-500' }}">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">📜 JavaScript</h3>
                    @if($jsExists)
                        <span class="text-2xl">✅</span>
                    @else
                        <span class="text-2xl">❌</span>
                    @endif
                </div>
                @if($jsExists)
                    <p class="text-green-600 text-sm">✅ Working fine</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $jsCount }} file(s) found</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-green-500 rounded-full" style="width: 100%"></div>
                    </div>
                @else
                    <p class="text-red-600 text-sm">❌ Missing assets</p>
                    <p class="text-xs text-gray-500 mt-1">Run build first</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-red-500 rounded-full animated" style="width: 10%"></div>
                    </div>
                @endif
            </div>
            
            <!-- CSS Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card border-l-4 {{ $cssExists ? 'border-green-500' : 'border-red-500' }}">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">🎨 CSS Styles</h3>
                    @if($cssExists)
                        <span class="text-2xl">✅</span>
                    @else
                        <span class="text-2xl">❌</span>
                    @endif
                </div>
                @if($cssExists)
                    <p class="text-green-600 text-sm">✅ Working fine</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $cssCount }} file(s) found</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 100%"></div>
                    </div>
                @else
                    <p class="text-red-600 text-sm">❌ Missing styles</p>
                    <p class="text-xs text-gray-500 mt-1">Run build first</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-red-500 rounded-full animated" style="width: 10%"></div>
                    </div>
                @endif
            </div>
            
            <!-- Storage Link Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card border-l-4 {{ $storageLinked ? 'border-green-500' : 'border-yellow-500' }}">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">💾 Storage Link</h3>
                    @if($storageLinked)
                        <span class="text-2xl">✅</span>
                    @else
                        <span class="text-2xl">⚠️</span>
                    @endif
                </div>
                @if($storageLinked)
                    <p class="text-green-600 text-sm">✅ Linked properly</p>
                    <p class="text-xs text-gray-500 mt-1">Storage accessible</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-green-500 rounded-full" style="width: 100%"></div>
                    </div>
                @else
                    <p class="text-yellow-600 text-sm">⚠️ Not linked</p>
                    <p class="text-xs text-gray-500 mt-1">Run: php artisan storage:link</p>
                    <div class="mt-3 progress-bar bg-gray-200">
                        <div class="h-full bg-yellow-500 rounded-full animated" style="width: 30%"></div>
                    </div>
                @endif
            </div>
            
        </div>
        
        <!-- Additional Info Cards -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            
            <!-- Build Info -->
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-3">📦 Build Information</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Latest Build</span>
                        <span class="font-medium">{{ $latestBuild->version ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Published At</span>
                        <span class="font-medium">{{ $latestBuild ? $latestBuild->published_at->format('d M Y h:i A') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Total Files</span>
                        <span class="font-medium">{{ $latestBuild->asset_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium {{ $latestBuild && $latestBuild->status == 'Success' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $latestBuild->status ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Fix -->
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h3 class="font-semibold text-gray-800 mb-3">🛠️ Quick Fix</h3>
                <div class="space-y-3">
                    @if(!$jsExists || !$cssExists)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-700">
                            ⚠️ Assets are missing. Run build command to fix.
                        </div>
                    @endif
                    
                    @if(!$storageLinked)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-700">
                            ⚠️ Storage link is missing. Run storage:link command.
                        </div>
                    @endif
                    
                    <div class="flex flex-wrap gap-2">
                        <form action="/publish-build" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600">
                                📦 Build Now
                            </button>
                        </form>
                        
                        <form action="/create-backup" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600">
                                💾 Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Help Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-6">
            <h3 class="font-semibold text-blue-800 mb-2">💡 What do these mean?</h3>
            <div class="grid md:grid-cols-2 gap-2 text-sm text-blue-700">
                <div>
                    <strong>✅ Working</strong> - Assets are properly loaded and accessible
                </div>
                <div>
                    <strong>❌ Missing</strong> - Need to run: <code class="bg-blue-100 px-2 py-1 rounded">npm run build</code>
                </div>
                <div>
                    <strong>🔗 Storage Link</strong> - Connection between storage and public folder
                </div>
                <div>
                    <strong>📊 Progress Bar</strong> - Shows asset availability percentage
                </div>
            </div>
        </div>
        
        <!-- Quick Commands -->
        <div class="bg-gray-800 rounded-lg p-5">
            <h3 class="text-white font-semibold mb-3">🛠️ Quick Commands</h3>
            <div class="grid md:grid-cols-2 gap-2 text-sm">
                <div class="bg-gray-900 rounded px-3 py-2">
                    <code class="text-green-400">npm run build</code>
                    <span class="text-gray-400 ml-3"># Compile assets</span>
                </div>
                <div class="bg-gray-900 rounded px-3 py-2">
                    <code class="text-green-400">php artisan storage:link</code>
                    <span class="text-gray-400 ml-3"># Create storage link</span>
                </div>
                <div class="bg-gray-900 rounded px-3 py-2">
                    <code class="text-green-400">php artisan serve</code>
                    <span class="text-gray-400 ml-3"># Start development server</span>
                </div>
                <div class="bg-gray-900 rounded px-3 py-2">
                    <code class="text-green-400">php artisan lasso:publish</code>
                    <span class="text-gray-400 ml-3"># Publish assets</span>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>