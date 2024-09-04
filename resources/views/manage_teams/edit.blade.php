<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team | {{ $clubName }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Edit Team</h2>
            <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $team->name }}" required>
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Team Logo</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="mt-2 h-24 w-24">
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update Team</button>
                    <a href="{{ route('teams.index') }}" class="text-blue-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
