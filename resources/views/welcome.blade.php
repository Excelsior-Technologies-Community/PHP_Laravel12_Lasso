{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Lasso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"> Laravel Lasso</h1>
            <p class="text-gray-600 mb-6">Asset Deployment Management System</p>
            
            <div class="space-y-3">
                <a href="/dashboard" class="block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    Go to Dashboard
                </a>
                <a href="/asset-status" class="block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition">
                    Check Asset Status
                </a>
            </div>
            
            <div class="mt-6 pt-6 border-t text-sm text-gray-500">
                Manage your builds and assets easily
            </div>
        </div>
    </div>
</body>
</html>