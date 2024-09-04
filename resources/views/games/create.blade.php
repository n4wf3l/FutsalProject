<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_match_title') }} | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <x-navbar />

    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg">
            <x-page-subtitle text="{{ __('messages.create_match') }}" />

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="add-match-form">
                @csrf

                <!-- Home Team -->
                <div class="mb-4">
                    <label for="home_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.home_team') }}</label>
                    <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">{{ __('messages.select_team') }}</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Away Team -->
                <div class="mb-4">
                    <label for="away_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.away_team') }}</label>
                    <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">{{ __('messages.select_team') }}</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Match Date -->
                <div class="mb-4">
                    <label for="match_date" class="block text-sm font-medium text-gray-700">{{ __('messages.match_date') }}</label>
                    <input type="date" name="match_date" id="match_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">
                    <button type="button" onclick="addMatchToQueue()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        {{ __('messages.add_match_to_queue') }}
                    </button>
                    <a href="{{ route('games.index') }}" class="text-blue-500 hover:underline">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>

        <!-- Match Queue -->
        <div class="max-w-md mx-auto bg-white p-8 shadow-lg rounded-lg mt-6">
            <x-page-subtitle text="{{ __('messages.match_queue') }}" />
            <ul id="match-queue" class="list-disc list-inside">
                <!-- Matches will be added here dynamically -->
            </ul>
            <button type="button" onclick="submitAllMatches()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700 mt-4">
                {{ __('messages.create_all_matches') }}
            </button>
        </div>
    </div>

    <x-footer />

    <script>
        let matchQueue = [];

        function addMatchToQueue() {
            const homeTeamId = document.getElementById('home_team_id').value;
            const homeTeamName = document.getElementById('home_team_id').options[document.getElementById('home_team_id').selectedIndex].text;
            const awayTeamId = document.getElementById('away_team_id').value;
            const awayTeamName = document.getElementById('away_team_id').options[document.getElementById('away_team_id').selectedIndex].text;
            const matchDate = document.getElementById('match_date').value;

            if (!homeTeamId || !awayTeamId) {
                alert('{{ __('messages.select_both_teams') }}');
                return;
            }

            if (homeTeamId === awayTeamId) {
                alert('{{ __('messages.different_teams_required') }}');
                return;
            }

            if (!matchDate) {
                alert('{{ __('messages.select_match_date') }}');
                return;
            }

            matchQueue.push({ homeTeamId, homeTeamName, awayTeamId, awayTeamName, matchDate });

            const matchQueueElement = document.getElementById('match-queue');
            const listItem = document.createElement('li');
            listItem.textContent = `${matchDate}: ${homeTeamName} vs ${awayTeamName}`;
            matchQueueElement.appendChild(listItem);

            document.getElementById('add-match-form').reset();
        }

        function submitAllMatches() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("games.storeMultiple") }}';
            form.innerHTML = `@csrf`;

            matchQueue.forEach((match, index) => {
                form.innerHTML += `
                    <input type="hidden" name="matches[${index}][home_team_id]" value="${match.homeTeamId}">
                    <input type="hidden" name="matches[${index}][away_team_id]" value="${match.awayTeamId}">
                    <input type="hidden" name="matches[${index}][match_date]" value="${match.matchDate}">
                `;
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
