<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #0f172a;
        }

        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="text-white min-h-screen p-10">

    <h1 class="text-4xl font-bold text-cyan-400 mb-10 text-center">
        Asset Deployment Dashboard 🚀
    </h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-xl mb-6 text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <div class="glass p-6 rounded-2xl">
            <h2 class="text-gray-400">Total Builds</h2>
            <p class="text-4xl font-bold mt-2">{{ $totalBuilds }}</p>
        </div>

        <div class="glass p-6 rounded-2xl">
            <h2 class="text-gray-400">Asset Files</h2>
            <p class="text-4xl font-bold mt-2">{{ $assetFiles }}</p>
        </div>

        <div class="glass p-6 rounded-2xl">
            <h2 class="text-gray-400">Latest Version</h2>
            <p class="text-4xl font-bold mt-2">
                {{ $latestBuild?->version ?? 'N/A' }}
            </p>
        </div>

    </div>

    <form action="/publish-build" method="POST" class="mb-10">
        @csrf

        <button
            class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold hover:scale-105 transition">
            Publish New Build
        </button>
    </form>

    <div class="glass rounded-2xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-cyan-500 text-black">
                <tr>
                    <th class="p-4 text-left">Version</th>
                    <th class="p-4 text-left">Assets</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Published At</th>
                </tr>
            </thead>

            <tbody>

                @forelse($builds as $build)
                    <tr class="border-b border-gray-700">

                        <td class="p-4">{{ $build->version }}</td>

                        <td class="p-4">{{ $build->asset_count }}</td>

                        <td class="p-4">
                            <span class="bg-green-500 px-3 py-1 rounded-full text-sm">
                                {{ $build->status }}
                            </span>
                        </td>

                        <td class="p-4">
                            {{ $build->published_at }}
                        </td>

                    </tr>
                @empty

                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400">
                            No Build History Found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</body>

</html>