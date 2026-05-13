<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Lasso Pro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #111827);
        }

        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center text-white">

    <div class="glass p-10 rounded-3xl shadow-2xl text-center w-[90%] md:w-[600px]">

        <h1 class="text-5xl font-bold text-cyan-400 mb-5">
            Laravel 12 Lasso 🚀
        </h1>

        <p class="text-gray-300 text-lg mb-8">
            Premium Asset Deployment Management Dashboard
        </p>

        <div class="flex justify-center gap-5 flex-wrap">

            <a href="/dashboard"
                class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold hover:scale-105 transition">
                Dashboard
            </a>

            <a href="/asset-status"
                class="bg-purple-500 px-6 py-3 rounded-xl font-bold hover:scale-105 transition">
                Asset Status
            </a>

        </div>

    </div>

</body>

</html>