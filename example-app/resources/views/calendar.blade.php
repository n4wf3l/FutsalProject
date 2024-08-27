<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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
    </style>
</head>
<body class="bg-gray-100" @if($backgroundImage) style="background: url('{{ asset('storage/' . $backgroundImage->image_path) }}') no-repeat center center fixed; background-size: cover;" @endif>
    <x-navbar />

    <header class="text-center my-12">
        <x-page-title subtitle=" 🗓️ Stay up to date with our comprehensive calendar, featuring all the upcoming futsal matches and key events.">
            {{ $championship->name }}
        </x-page-title>
        <x-validation-messages />
    </header>

    @auth
    <div class="admin-buttons">
        <a href="#" data-bs-toggle="modal" data-bs-target="#championshipModal">Settings</a>
    </div>
    @endauth

    <!-- Modal for configuring the championship and season -->
    <div class="modal fade" id="championshipModal" tabindex="-1" aria-labelledby="championshipModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="championshipModalLabel">Championship Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('championship.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Championship Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $championship->name ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="season" class="form-label">Season</label>
                            <input type="text" class="form-control" id="season" name="season" value="{{ $championship->season ?? '' }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <main class="py-12">
        <a id="ranking-section"></a>
        @if($championship)
        <x-page-subtitle text="Ranking - {{ $championship->name }} {{ $championship->season }}" />
        @else
        <x-page-subtitle text="No championship data available" />
        @endif
        <p class="text-center text-gray-600 italic">Green: Season champion of {{ $championship->name }}</p>
        <p class="text-center text-gray-600 italic">Yellow: Compete in playoffs against the top two from D2</p>
        <p class="text-center text-gray-600 italic mb-4">Red: Relegation to D2</p>
        @auth
        <div class="admin-buttons">
            <a href="{{ route('manage_teams.create') }}">Add a Team</a>
        </div>
        @endauth

        <div class="container table-container">
            <table class="rounded-table">
                <thead data-aos="flip-left">
                    <tr>
                        <th>#</th>
                        <th>Club</th>
                        <th>Pts</th>
                        <th>Jo</th>
                        <th>G</th>
                        <th>N</th>
                        <th>P</th>
                        <th>Diff</th>
                        @auth
                        <th>Actions</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                    <tr data-aos="flip-left" @if($loop->first)
                        style="background-color: #d4edda;"
                        @elseif($loop->iteration >= count($teams) - 3 && $loop->iteration <= count($teams) - 2)
                        style="background-color: #fff3cd;"
                        @elseif($loop->iteration >= count($teams) - 1)
                        style="background-color: #f8d7da;"
                        @endif
                        >
                        <td data-aos="flip-up">{{ $loop->iteration }}</td>
                        <td data-aos="flip-up" class="club-name">
                            @if($team->logo_path)
                            <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }} Logo" class="club-logo">
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
                        <td data-aos="flip-up">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editTeamModal-{{ $team->id }}">🛠️</button>
                            <div class="modal fade" id="editTeamModal-{{ $team->id }}" tabindex="-1" aria-labelledby="editTeamModalLabel-{{ $team->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTeamModalLabel-{{ $team->id }}">Edit Team</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('manage_teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="team_name" class="block text-sm font-medium text-gray-700">Team Name</label>
                                                    <input type="text" name="name" id="team_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $team->name }}" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="logo_path" class="block text-sm font-medium text-gray-700">Team Logo</label>
                                                    <input type="file" name="logo_path" id="logo_path" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    @if($team->logo_path)
                                                    <img src="{{ Storage::url($team->logo_path) }}" alt="{{ $team->name }} Logo" style="height: 50px; margin-top: 10px;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

                            <form action="{{ route('manage_teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('Are you sure you want to delete this team?');">X</button>
                            </form>
                        </td>
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="mt-20 mb-20">

        <a id="calendar-section"></a>
        <x-page-subtitle text="Match Calendar - {{ $clubName }}" />
        <p class="text-center text-gray-600 italic mb-4">All home matches take place at {{ $clubLocation }}.</p>
        @auth
        <div class="admin-buttons">
            <a href="{{ route('games.create') }}" class="btn btn-primary">Create a Match</a>
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
                <label for="team_filter">Show matches of:</label>
                <select name="team_filter" id="team_filter" onchange="this.form.submit()">
                    <option value="all_teams" {{ request('team_filter') == 'all_teams' ? 'selected' : '' }}>All Teams</option>
                    <option value="specific_team" {{ request('team_filter') == 'specific_team' ? 'selected' : '' }}>
                        {{ $clubName }} <!-- Display full club name here -->
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <!-- Filter by date -->
                <label for="date_filter">Show:</label>
                <select name="date_filter" id="date_filter" onchange="this.form.submit()">
                    <option value="results_and_upcoming" {{ request('date_filter') == 'results_and_upcoming' ? 'selected' : '' }}>Results + Upcoming Matches</option>
                    <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming Matches</option>
                    <option value="results" {{ request('date_filter') == 'results' ? 'selected' : '' }}>Results</option>
                </select>
            </div>
        </form>

        <div class="container table-container">
            <table class="rounded-table">
                <thead data-aos="flip-up">
                    <tr>
                        <th>Date</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Score</th>
                        @auth
                        <th>Actions</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($games->sortBy('match_date') as $game)
                    <tr>
                        <td data-aos="flip-up">{{ \Carbon\Carbon::parse($game->match_date)->format('d-m-Y') }}</td>
                        <td data-aos="flip-up">
                            <div style="display: flex; align-items: center;">
                                @if($game->homeTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} Logo" class="club-logo">
                                @endif
                                {{ $game->homeTeam->name }}
                            </div>
                        </td>
                        <td data-aos="flip-up">
                            <div style="display: flex; align-items: center;">
                                @if($game->awayTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} Logo" class="club-logo">
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
                                <button type="submit" class="update-scores-button">OK</button>
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
                                            <h5 class="modal-title" id="editGameModalLabel-{{ $game->id }}">Edit Match</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('games.update', $game->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label for="match_date" class="block text-sm font-medium text-gray-700">Match Date</label>
                                                    <input type="date" name="match_date" id="match_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $game->match_date }}" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="home_team_id" class="block text-sm font-medium text-gray-700">Home Team</label>
                                                    <select name="home_team_id" id="home_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $team->id == $game->home_team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="away_team_id" class="block text-sm font-medium text-gray-700">Away Team</label>
                                                    <select name="away_team_id" id="away_team_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $team->id == $game->away_team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('Are you sure you want to delete this game?');">X</button>
                            </form>
                        </td>
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @auth
        <div class="admin-buttons">
            <a href="#" onclick="event.preventDefault(); document.getElementById('reset-scores-form').submit();" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700">Reset</a>

            <form id="reset-scores-form" action="{{ route('reset.scores') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        @endauth
    </main>

    <x-footerforhome />
</body>
</html>
