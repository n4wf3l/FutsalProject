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

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Create a new match for {{ $clubName }}. Add details such as home team, away team, and match date.">
    <meta name="keywords" content="create match, {{ $clubName }}, futsal, sports">
    <meta property="og:title" content="Create Match - {{ $clubName }}">
    <meta property="og:description" content="Create and manage matches in {{ $clubName }}. Add match details and manage your team's schedule.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-gray-100">

    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="">
            {{ __('messages.create_match') }}
        </x-page-title>
    </header>

    <div class="container mx-auto mt-8 p-8 rounded-lg shadow-md border border-gray-300 max-w-3xl" style="margin-bottom: 50px;">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Please fix the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="add-match-form">
            @csrf

            <!-- Home Team -->
            <div class="mb-6">
                <label for="home_team_id" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.home_team') }}:</label>
                <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
                    <option value="">{{ __('messages.select_team') }}</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Away Team -->
            <div class="mb-6">
                <label for="away_team_id" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.away_team') }}:</label>
                <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
                    <option value="">{{ __('messages.select_team') }}</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Match Date -->
            <div class="mb-6">
                <label for="match_date" class="block text-lg font-medium text-gray-700 mb-2">{{ __('messages.match_date') }}:</label>
                <input type="date" name="match_date" id="match_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center mt-8 space-x-4">
                <button type="button" onclick="addMatchToQueue()" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                    style="
                        background-color: {{ $primaryColor }};
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
                    {{ __('messages.add_match_to_queue') }}
                </button>

                <a href="{{ route('games.index') }}" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200 text-center"
                    style="
                        background-color: #DC2626;
                        font-size: 15px;
                        transition: background-color 0.3s ease;
                    "
                    onmouseover="this.style.backgroundColor='#B91C1C'"
                    onmouseout="this.style.backgroundColor='#DC2626'">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Match Queue -->
    <div class="container mx-auto mt-8 p-8 rounded-lg shadow-md border border-gray-300 max-w-3xl" style="margin-bottom: 50px;">
        <x-page-title subtitle="">
            {{ __('messages.match_queue') }}
        </x-page-title>
        <ul id="match-queue" class="list-disc list-inside mt-4">
            <!-- Matches will be added here dynamically -->
        </ul>
        <div class="flex justify-center mt-8">
            <button type="button" onclick="submitAllMatches()" class="text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-200"
                style="
                    background-color: #38A169;
                    font-size: 15px;
                    transition: background-color 0.3s ease;
                "
                onmouseover="this.style.backgroundColor='#2F855A'"
                onmouseout="this.style.backgroundColor='#38A169'">
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
