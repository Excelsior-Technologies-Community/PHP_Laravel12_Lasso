{{-- resources/views/asset-status.blade.php --}}
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
        .status-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="p-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">🔍 Asset Health Checker</h1>
                    <p class="text-gray-500 text-sm mt-1">Check if your assets are working properly</p>
                </div>
                <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">← Back</a>
            </div>
        </div>
        
        <!-- Status Cards -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            
            <!-- JS Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">JavaScript</h3>
                    @if($jsExists)
                        <span class="text-2xl"></span>
                    @else
                        <span class="text-2xl"></span>
                    @endif
                </div>
                @if($jsExists)
                    <p class="text-green-600 text-sm">Working fine</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $jsCount }} file(s) found</p>
                @else
                    <p class="text-red-600 text-sm">Missing assets</p>
                    <p class="text-xs text-gray-500 mt-1">Run build first</p>
                @endif
            </div>
            
            <!-- CSS Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">CSS Styles</h3>
                    @if($cssExists)
                        <span class="text-2xl"></span>
                    @else
                        <span class="text-2xl"></span>
                    @endif
                </div>
                @if($cssExists)
                    <p class="text-green-600 text-sm">Working fine</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $cssCount }} file(s) found</p>
                @else
                    <p class="text-red-600 text-sm">Missing styles</p>
                    <p class="text-xs text-gray-500 mt-1">Run build first</p>
                @endif
            </div>
            
            <!-- Storage Link Status -->
            <div class="bg-white rounded-lg shadow-sm p-5 status-card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">Storage Link</h3>
                    @if($storageLinked)
                        <span class="text-2xl"></span>
                    @else
                        <span class="text-2xl"></span>
                    @endif
                </div>
                @if($storageLinked)
                    <p class="text-green-600 text-sm">Linked properly</p>
                    <p class="text-xs text-gray-500 mt-1">Storage accessible</p>
                @else
                    <p class="text-yellow-600 text-sm">Not linked</p>
                    <p class="text-xs text-gray-500 mt-1">Run: php artisan storage:link</p>
                @endif
            </div>
            
        </div>
        
        <!-- Help Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
            <h3 class="font-semibold text-blue-800 mb-2"> What do these mean?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• <strong>JavaScript/CSS Assets</strong> - Your compiled frontend files</li>
                <li>• <strong>Storage Link</strong> - Connection between storage and public folder</li>
                <li>• <strong>Working</strong> - Assets are properly loaded and accessible</li>
                <li>• <strong>Missing</strong> - Need to run: npm run build</li>
            </ul>
        </div>
        
        <!-- Quick Commands -->
        <div class="bg-gray-800 rounded-lg p-5 mt-6">
            <h3 class="text-white font-semibold mb-3">🛠️ Quick Commands</h3>
            <div class="space-y-2 text-sm">
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
            </div>
        </div>
        
    </div>
</body>
</html>