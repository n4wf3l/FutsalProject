<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Team | {{ $clubName }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Create New Team</h2>
            <form action="{{ route('manage_teams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Team Logo</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Create Team</button>
                    <a href="{{ route('teams.index') }}" class="text-blue-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
