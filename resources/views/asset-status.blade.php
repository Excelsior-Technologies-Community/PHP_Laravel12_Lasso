<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Status</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #020617;
        }

        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="text-white min-h-screen p-10">

    <h1 class="text-4xl font-bold text-center text-cyan-400 mb-10">
        Asset Health Checker 🔍
    </h1>

    <div class="grid md:grid-cols-3 gap-6">

        <div class="glass p-6 rounded-2xl">

            <h2 class="text-xl mb-4">JS Assets</h2>

            @if($jsExists)
                <span class="bg-green-500 px-4 py-2 rounded-full">
                    Working ✅
                </span>
            @else
                <span class="bg-red-500 px-4 py-2 rounded-full">
                    Missing ❌
                </span>
            @endif

        </div>

        <div class="glass p-6 rounded-2xl">

            <h2 class="text-xl mb-4">CSS Assets</h2>

            @if($cssExists)
                <span class="bg-green-500 px-4 py-2 rounded-full">
                    Working ✅
                </span>
            @else
                <span class="bg-red-500 px-4 py-2 rounded-full">
                    Missing ❌
                </span>
            @endif

        </div>

        <div class="glass p-6 rounded-2xl">

            <h2 class="text-xl mb-4">Storage Link</h2>

            @if($storageLinked)
                <span class="bg-green-500 px-4 py-2 rounded-full">
                    Linked ✅
                </span>
            @else
                <span class="bg-red-500 px-4 py-2 rounded-full">
                    Not Linked ❌
                </span>
            @endif

        </div>

    </div>

    <div class="mt-10 text-center">

        <a href="/dashboard"
            class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold">
            Back Dashboard
        </a>

    </div>

</body>

</html>