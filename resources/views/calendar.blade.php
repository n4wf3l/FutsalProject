<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.calendar') }} | {{ $clubName ?? 'Default Club Name' }}</title>
    @if(isset($logoPath))
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- Meta Tags for SEO -->
<meta name="description" content="@lang('messages.calendar_subtitle') - {{ $championship->name ?? 'Unknown Championship' }} {{ $championship->season ?? '' }}. Discover the match calendar and follow the performances of {{ $clubName ?? 'Default Club Name' }} in {{ $clubLocation ?? 'Unknown Location' }}.">
<meta name="keywords" content="futsal, {{ $clubName ?? 'Default Club Name' }}, {{ $championship->name ?? 'Unknown Championship' }}, match calendar, {{ $clubLocation ?? 'Unknown Location' }}, sports">
<meta property="og:title" content="@lang('messages.calendar') - {{ $clubName ?? 'Default Club Name' }} in {{ $clubLocation ?? 'Unknown Location' }}">
<meta property="og:description" content="@lang('messages.calendar_subtitle') - Follow the matches and results of {{ $clubName ?? 'Default Club Name' }}.">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        body {
            background-color: #f3f4f6;
        }

        .container {
            margin: 0 auto;
        }

        .table-container {
            display: flex;
            justify-content: center;
            max-width: 1000px;
            margin-bottom: 20px;
            overflow-x: auto; /* Ensure tables can scroll on small screens */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: {{ $primaryColor }};
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .club-name {
            display: flex;
            align-items: center;
            justify-content: center; /* Centered logos and names */
        }

        .club-logo {
            height: 24px;
            margin-right: 10px;
        }

        .calendar-title {
            font-size: 2rem;
            margin-top: 4rem;
            text-align: center;
            color: {{ $primaryColor }};
            font-weight: bold;
        }

        .admin-buttons {
            text-align: center;
        }

        .admin-buttons a {
            display: inline-block;
            background-color: {{ $primaryColor }};
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
        }

        .admin-buttons a:hover {
            background-color: {{ $secondaryColor }};
        }

        .score-input {
            width: 60px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .update-scores-button {
            background-color: {{ $primaryColor }};
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .update-scores-button:hover {
            background-color: darken({{ $primaryColor }}, 10%);
        }

        .rounded-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 4px solid {{ $primaryColor }};
            border-radius: 15px;
            overflow: hidden;
        }

        .rounded-table thead tr {
            background-color: {{ $primaryColor }};
            color: white;
        }

        .rounded-table th,
        .rounded-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .rounded-table tbody tr:last-child td {
            border-bottom: none;
        }

        .rounded-table tbody tr {
            background-color: #ffffff;
        }

        .rounded-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .rounded-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .rounded-table th:first-child,
        .rounded-table td:first-child {
            border-top-left-radius: 15px;
        }

        .rounded-table th:last-child,
        .rounded-table td:last-child {
            border-top-right-radius: 15px;
        }

        .rounded-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 15px;
        }

        .rounded-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 15px;
        }

        .gray-text {
            color: lightgray;
            font-weight: normal;
        }

        .green-text {
            color: green;
            font-weight: bold;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .table-container {
                padding: 0 10px;
                overflow-x: scroll; /* Allow scrolling if the table is too wide */
            }

            th, td {
                font-size: 14px;
                padding: 8px;
            }

            .score-input {
                width: 40px;
                padding: 6px;
            }

            .admin-buttons a {
                padding: 8px 15px;
                margin: 3px;
                font-size: 14px;
            }

            .calendar-title {
                font-size: 1.5rem;
                margin-top: 2rem;
            }

            .filter-form {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-group {
                margin-bottom: 10px;
                width: 100%;
            }

            .filter-form label {
                margin-bottom: 5px;
            }

            .filter-form select {
                width: 100%;
            }

            .rounded-table th, 
            .rounded-table td {
                padding: 8px;
            }
        }
        .page-title {
    display: flex;
    align-items: center;
    width: 100%;
}

.page-title::before {
    content: "";
    display: block;
    width: 60px; /* Vous pouvez ajuster selon le design souhaité */
    height: 5px; /* Épaisseur de la barre */
    background-color: red;
    margin-right: 15px; /* Espace entre la barre et le texte */
}
    </style>
</head>
<body class="bg-gray-100" @if(isset($backgroundImage)) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle="{{ __('messages.calendar_subtitle') }}">
{{ $championship->name ?? 'Not available' }}
        </x-page-title>
        <x-validation-messages />
    </header>

    @auth
    <div class="admin-buttons">
        <a href="#" data-bs-toggle="modal" data-bs-target="#championshipModal">{{ __('messages.settings') }}</a>
    </div>
    @endauth

    <!-- Modal for configuring the championship and season -->
    <div class="modal fade" id="championshipModal" tabindex="-1" aria-labelledby="championshipModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="championshipModalLabel">{{ __('messages.championship_settings') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                </div>
                <form action="{{ route('championship.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.championship_name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $championship->name ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="season" class="form-label">{{ __('messages.season') }}</label>
                            <input type="text" class="form-control" id="season" name="season" value="{{ $championship->season ?? '' }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <main class="py-12">
        <a id="ranking-section"></a>
       @if(isset($championship))
        <x-page-subtitle text="{{ __('messages.ranking') }} - {{ $championship->name }} {{ $championship->season }}" />
        @else
        <x-page-subtitle text="{{ __('messages.no_championship_data') }}" />
        @endif
        <p class="text-center text-gray-600 italic">{{ __('messages.green_champion') }} {{ $championship->name ?? 'Not available' }}</p>
        <p class="text-center text-gray-600 italic">{{ __('messages.yellow_playoffs') }}</p>
        <p class="text-center text-gray-600 italic mb-4">{{ __('messages.red_relegation') }}</p>
        @auth
        <div class="admin-buttons">
            <a href="{{ route('manage_teams.create') }}">{{ __('messages.add_team') }}</a>
        </div>
        @endauth

        <div class="container table-container">
            <table class="rounded-table">
                <thead data-aos="flip-left">
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.club') }}</th>
                        <th>{{ __('messages.pts') }}</th>
                        <th>{{ __('messages.jo') }}</th>
                        <th>{{ __('messages.g') }}</th>
                        <th>{{ __('messages.n') }}</th>
                        <th>{{ __('messages.p') }}</th>
                        <th>{{ __('messages.diff') }}</th>
                        @auth
                        <th>{{ __('messages.actions') }}</th>
                        @endauth
                    </tr>
                </thead>
               <tbody>
            @if($teams->isEmpty())
                <tr>
                    <td colspan="9" class="text-center text-gray-600">{{ __('messages.no_teams') }}</td>
                </tr>
            @else
                @foreach($teams as $team)
                    <tr>
                        <td data-aos="flip-up">{{ $loop->iteration }}</td>
                        <td data-aos="flip-up" class="club-name">
                            @if($team->logo_path)
                                <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }} {{ __('messages.logo') }}" class="club-logo">
                            @endif
                            <span>{{ $team->name }}</span>
                        </td>
                        <td data-aos="flip-up">{{ $team->points }}</td>
                        <td data-aos="flip-up">{{ $team->games_played }}</td>
                        <td data-aos="flip-up">{{ $team->wins }}</td>
                        <td data-aos="flip-up">{{ $team->draws }}</td>
                        <td data-aos="flip-up">{{ $team->losses }}</td>
                        <td data-aos="flip-up">{{ $team->goal_difference }}</td>
                        @auth
                        <td>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editTeamModal-{{ $team->id }}">🛠️</button>
                            <div class="modal fade" id="editTeamModal-{{ $team->id }}" tabindex="-1" aria-labelledby="editTeamModalLabel-{{ $team->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTeamModalLabel-{{ $team->id }}">{{ __('messages.edit_team') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                                        </div>
                                        <form action="{{ route('manage_teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="team_name" class="block text-sm font-medium text-gray-700">{{ __('messages.team_name') }}</label>
                                                    <input type="text" name="name" id="team_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $team->name }}" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="logo_path" class="block text-sm font-medium text-gray-700">{{ __('messages.team_logo') }}</label>
                                                    <input type="file" name="logo_path" id="logo_path" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    @if($team->logo_path)
                                                    <img src="{{ Storage::url($team->logo_path) }}" alt="{{ $team->name }} {{ __('messages.logo') }}" style="height: 50px; margin-top: 10px;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

                            <form action="{{ route('manage_teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('{{ __('messages.confirm_delete_team') }}');">X</button>
                            </form>
                        </td>
                        @endauth
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
        </div>

        <hr class="mt-20 mb-20">

        <a id="calendar-section"></a>
        <x-page-subtitle text="{{ __('messages.match_calendar') }} - {{ $clubName }}" />
        <p class="text-center text-gray-600 italic mb-4">{{ __('messages.home_matches_location') }} {{ $clubLocation }}.</p>
        @auth
        <div class="admin-buttons">
            <a href="{{ route('games.create') }}" class="btn btn-primary">{{ __('messages.create_match') }}</a>
        </div>
        @endauth

        <style>
            /* Centering and styling the form */
            .filter-form {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .filter-form .filter-group {
                margin: 0 15px;
                display: flex;
                align-items: center;
            }

            .filter-form label {
                margin-right: 8px;
                font-weight: bold;
                color: #333;
            }

            .filter-form select {
                padding: 6px 12px;
                border-radius: 4px;
                border: 1px solid #ccc;
                background-color: #f9f9f9;
                color: #333;
                transition: all 0.3s ease;
            }

            .filter-form select:hover {
                border-color: #999;
                background-color: #e9e9e9;
            }

            @media (max-width: 768px) {
                .filter-form {
                    flex-direction: column;
                }

                .filter-form .filter-group {
                    margin-bottom: 10px;
                    width: 100%;
                }

                .filter-form label {
                    margin-bottom: 5px;
                }

                .filter-form select {
                    width: 100%;
                }
            }
        </style>

        <form method="GET" action="{{ route('calendar.show') }}" class="filter-form">
            <div class="filter-group">
                <!-- Filter by team -->
                <label for="team_filter">{{ __('messages.show_matches_of') }}:</label>
                <select name="team_filter" id="team_filter" onchange="this.form.submit()">
                    <option value="all_teams" {{ request('team_filter') == 'all_teams' ? 'selected' : '' }}>{{ __('messages.all_teams') }}</option>
                    <option value="specific_team" {{ request('team_filter') == 'specific_team' ? 'selected' : '' }}>
                        {{ $clubName }} <!-- Display full club name here -->
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <!-- Filter by date -->
                <label for="date_filter">{{ __('messages.show') }}:</label>
                <select name="date_filter" id="date_filter" onchange="this.form.submit()">
                    <option value="results_and_upcoming" {{ request('date_filter') == 'results_and_upcoming' ? 'selected' : '' }}>{{ __('messages.results_and_upcoming') }}</option>
                    <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>{{ __('messages.upcoming_matches') }}</option>
                    <option value="results" {{ request('date_filter') == 'results' ? 'selected' : '' }}>{{ __('messages.results') }}</option>
                </select>
            </div>
        </form>

        <div class="container table-container">
            <table class="rounded-table">
                <thead data-aos="flip-up">
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.home') }}</th>
                        <th>{{ __('messages.away') }}</th>
                        <th>{{ __('messages.score') }}</th>
                        @auth
                        <th>{{ __('messages.actions') }}</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                     @if($games->isEmpty())
                <tr>
                    <td colspan="5" class="text-center text-gray-600">{{ __('messages.no_games') }}</td>
                </tr>
            @else
                    @foreach(($games ?? collect())->sortBy('match_date') as $game)
                    <tr>
                        <td data-aos="flip-up">{{ \Carbon\Carbon::parse($game->match_date)->format('d-m-Y') }}</td>
                        <td data-aos="flip-up">
                            <div style="display: flex; align-items: center;">
                                @if($game->homeTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} {{ __('messages.logo') }}" class="club-logo">
                                @endif
                                {{ $game->homeTeam->name }}
                            </div>
                        </td>
                        <td data-aos="flip-up">
                            <div style="display: flex; align-items: center;">
                                @if($game->awayTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} {{ __('messages.logo') }}" class="club-logo">
                                @endif
                                {{ $game->awayTeam->name }}
                            </div>
                        </td>
                        <td data-aos="flip-up">
                            @auth
                            <form action="{{ route('games.updateScores', $game->id) }}" method="POST">
                                @csrf
                                <input type="number" name="home_team_score" value="{{ old('home_team_score', $game->home_score) }}" min="0" class="score-input {{ $game->home_score !== null ? 'green-text' : 'gray-text' }}">
                                <span> - </span>
                                <input type="number" name="away_team_score" value="{{ old('away_team_score', $game->away_score) }}" min="0" class="score-input {{ $game->away_score !== null ? 'green-text' : 'gray-text' }}">
                                <button type="submit" class="update-scores-button">{{ __('messages.ok') }}</button>
                            </form>
                            @else
                            {{ $game->home_score }} - {{ $game->away_score }}
                            @endauth
                        </td>
                        @auth
                        <td>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editGameModal-{{ $game->id }}">🛠️</button>

                            <div class="modal fade" id="editGameModal-{{ $game->id }}" tabindex="-1" aria-labelledby="editGameModalLabel-{{ $game->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editGameModalLabel-{{ $game->id }}">{{ __('messages.edit_match') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                                        </div>
                                        <form action="{{ route('games.update', $game->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="match_date" class="block text-sm font-medium text-gray-700">{{ __('messages.match_date') }}</label>
                                                    <input type="date" name="match_date" id="match_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $game->match_date }}" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="home_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.home_team') }}</label>
                                                    <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $team->id == $game->home_team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="away_team_id" class="block text-sm font-medium text-gray-700">{{ __('messages.away_team') }}</label>
                                                    <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $team->id == $game->away_team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                                                <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('{{ __('messages.confirm_delete_match') }}');">X</button>
                            </form>
                        </td>
                        @endauth
                    </tr>
                    @endforeach
                                @endif
                </tbody>
            </table>
        </div>

        @auth
        <div class="admin-buttons">
            <a href="#" onclick="event.preventDefault(); document.getElementById('reset-scores-form').submit();" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700">{{ __('messages.reset') }}</a>

            <form id="reset-scores-form" action="{{ route('reset.scores') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        @endauth
    </main>

    <x-footerforhome />
</body>
</html>
