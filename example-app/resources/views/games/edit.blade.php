<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_match_title') }} | {{ $clubName }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">{{ __('messages.edit_match') }}</h2>
            <form action="{{ route('games.update', $game->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Home Team -->
                <div class="mb-4">
                    <label for="home_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.home_team') }}</label>
                    <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Away Team -->
                <div class="mb-4">
                    <label for="away_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.away_team') }}</label>
                    <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Match Date -->
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">{{ __('messages.match_date') }}</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $game->date->format('Y-m-d') }}" required>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        {{ __('messages.update_match') }}
                    </button>
                    <a href="{{ route('games.index') }}" class="text-blue-500 hover:underline">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
