<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match | {{ $clubName }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Edit Match</h2>
            <form action="{{ route('games.update', $game->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="home_team_id" class="block text-sm font-medium text-gray-700">Home Team</label>
                    <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="away_team_id" class="block text-sm font-medium text-gray-700">Away Team</label>
                    <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Match Date</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $game->date->format('Y-m-d') }}" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update Match</button>
                    <a href="{{ route('games.index') }}" class="text-blue-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
