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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .highlight-border {
    border: 2px solid red;
}
        .modal-backdrop { opacity: 0.5 !important; }

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

        .bg-success-light {
    background-color: #d4edda !important; /* Vert clair */
}

.bg-warning-light {
    background-color: #fff3cd !important; /* Jaune clair */
}

.bg-danger-light {
    background-color: #f8d7da !important; /* Rouge clair */
}

.points-cell {
    font-weight: bold;
    font-size: 1.2em; /* Augmentez cette valeur pour agrandir encore plus */
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

        /* Styles généraux pour le formulaire */
.filter-form {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
}

/* Styles spécifiques pour les écrans de mobile */
@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-group label {
        font-size: 1rem;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .filter-group select {
        width: 100%;
        padding: 0.5rem;
        font-size: 1rem;
    }
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
    <a href="#" 
    data-bs-toggle="modal" 
    data-bs-target="#championshipModal"
    style="
        display: inline-block;
        background-color: {{ $primaryColor }};
        color: white;
        font-family: 'Bebas Neue', sans-serif;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 50px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        letter-spacing: 1px;
        text-decoration: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    "
    onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
    onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';">
    {{ __('messages.settings') }}
</a>
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
        <div class="admin-buttons mb-10">
        <a href="{{ route('manage_teams.create') }}" 
   style="
       display: inline-block;
       background-color: {{ $primaryColor }};
       color: white;
       font-family: 'Bebas Neue', sans-serif;
       font-weight: bold;
       padding: 10px 20px;
       border-radius: 50px;
       cursor: pointer;
       transition: background-color 0.3s ease, transform 0.2s ease;
       letter-spacing: 1px;
       text-decoration: none;
       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
   "
   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';">
    {{ __('messages.add_team') }}
</a>
        </div>
        @endauth

        
        <div class="container table-container transform transition-transform duration-200 hover:scale-105">
            <table id="teams" class="rounded-table">
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
            <tr class="
                @if($loop->first) bg-success-light
                @elseif($loop->iteration >= $teams->count() - 1) bg-danger-light
                @elseif($loop->iteration >= $teams->count() - 3) bg-warning-light
                @endif
            ">
            <td>{{ $loop->iteration }}</td>
                <td class="club-name" style="{{ $team->name === $clubName ? 'border: 2px solid red;' : '' }}">
                    @if($team->logo_path)
                        <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }} {{ __('messages.logo') }}" class="club-logo">
                    @endif
                    <span>{{ $team->name }}</span>
                </td>
        <td class="points-cell">{{ $team->points }}</td>
        <td>{{ $team->games_played }}</td>
        <td>{{ $team->wins }}</td>
        <td>{{ $team->draws }}</td>
        <td>{{ $team->losses }}</td>
        <td>{{ $team->goal_difference }}</td>
        @auth
        <td>
            <!-- Action buttons -->
            <button type="button" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110" 
                    data-bs-toggle="modal" 
                    onclick="openEditTeamModal({{ $team->id }}, '{{ $team->name }}', '{{ asset('storage/' . $team->logo_path) }}')">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('manage_teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" onclick="return confirm('{{ __('messages.confirm_delete_team') }}');">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </td>
        @endauth
    </tr>
@endforeach
            @endif
        </tbody>
    </table>

    
        </div>
<!-- Affichage de la date de dernière mise à jour et du nom de l'utilisateur -->
@if($lastUpdatedGame)
    @if($lastUpdatedGame->updated_at && $lastUpdatedGame->updatedBy)
        <p class="text-center text-gray-500 mt-4">
            {{ __('messages.updated_on') }} {{ $lastUpdatedGame->updated_at->translatedFormat('d F Y') }} {{ __('messages.by') }} {{ $lastUpdatedGame->updatedBy->name }}
        </p>
    @elseif($lastUpdatedGame->updated_at)
        <p class="text-center text-gray-500 mt-4">
            {{ __('messages.updated_on') }} {{ $lastUpdatedGame->updated_at->translatedFormat('d F Y') }}
        </p>
    @endif
@endif
        <hr class="mt-20 mb-20">

        <a id="calendar-section"></a>
        <x-page-subtitle text="{{ __('messages.match_calendar') }} - {{ $clubName }}" />
        <p class="text-center text-gray-600 italic mb-4">{{ __('messages.home_matches_location') }} {{ $clubLocation }}.</p>
        @auth
        <div class="admin-buttons">
        <a href="{{ route('games.create') }}" 
   style="
       display: inline-block;
       background-color: {{ $primaryColor }};
       color: white;
       font-family: 'Bebas Neue', sans-serif;
       font-weight: bold;
       padding: 10px 20px;
       border-radius: 50px;
       cursor: pointer;
       transition: background-color 0.3s ease, transform 0.2s ease;
       letter-spacing: 1px;
       text-decoration: none;
       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
   "
   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';">
    {{ __('messages.create_match') }}
</a>
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
        <!-- Filtre par équipe -->
        <label for="team_filter">{{ __('messages.show_matches_of') }}:</label>
        <select name="team_filter" id="team_filter">
            <option value="all" {{ request('team_filter') == 'all' ? 'selected' : '' }}>
                {{ __('messages.all_teams') }}
            </option>
            @if(!$teamNotFound)
                <option value="club" {{ request('team_filter', 'club') == 'club' ? 'selected' : '' }}>
                    {{ $clubName }}
                </option>
            @endif
        </select>
    </div>

    <!-- Filtre par date -->
    <div class="filter-group">
        <label for="date_filter">{{ __('messages.show') }}:</label>
        <select name="date_filter" id="date_filter">
            <option value="results_and_upcoming" {{ request('date_filter') == 'results_and_upcoming' ? 'selected' : '' }}>
                {{ __('messages.results_and_upcoming') }}
            </option>
            <option value="upcoming" {{ request('date_filter', 'upcoming') == 'upcoming' ? 'selected' : '' }}>
                {{ __('messages.upcoming_matches') }}
            </option>
            <option value="results" {{ request('date_filter') == 'results' ? 'selected' : '' }}>
                {{ __('messages.results') }}
            </option>
        </select>
    </div>
</form>


        <div class="container table-container transform transition-transform duration-200 hover:scale-105">
            <table id="games" class="rounded-table">
                <thead data-aos="flip-up">
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.home') }}</th>
                        <th>{{ __('messages.score') }}</th>
                        <th>{{ __('messages.away') }}</th>
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
                        <td>{{ \Carbon\Carbon::parse($game->match_date)->format('d-m-Y') }}</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                @if($game->homeTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->homeTeam->logo_path) }}" alt="{{ $game->homeTeam->name }} {{ __('messages.logo') }}" class="club-logo">
                                @endif
                                {{ $game->homeTeam->name }}
                            </div>
                        </td>
                        <td>
    @auth
    <form action="/games/{{ $game->id }}/scores" 
      method="POST" 
      class="update-score-form" 
      style="display: flex; align-items: center;">
    @csrf
    <input type="number" name="home_team_score" 
           value="{{ $game->home_score ?? '' }}" 
           min="0" 
           class="form-control score-input me-1" 
           placeholder="Home" 
           style="width: 60px;" 
           required>
    <span class="mx-1">-</span>
    <input type="number" name="away_team_score" 
           value="{{ $game->away_score ?? '' }}" 
           min="0" 
           class="form-control score-input me-1" 
           placeholder="Away" 
           style="width: 60px;" 
           required>
    <button type="submit" class="btn btn-sm btn-success">{{ __('messages.ok') }}</button>
</form>
    @else
        @if($game->home_score !== null && $game->away_score !== null)
            {{ $game->home_score }} - {{ $game->away_score }}
        @else
            ---
        @endif
    @endauth
</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                @if($game->awayTeam->logo_path)
                                <img src="{{ asset('storage/' . $game->awayTeam->logo_path) }}" alt="{{ $game->awayTeam->name }} {{ __('messages.logo') }}" class="club-logo">
                                @endif
                                {{ $game->awayTeam->name }}
                            </div>
                        </td>
                
                        @auth
                        <td>
                        <button type="button" data-bs-toggle="modal" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110" data-bs-target="#editGameModal-{{ $game->id }}">
    <i class="fas fa-edit"></i>
</button>


                            <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" onclick="return confirm('{{ __('messages.confirm_delete_match') }}');">
        <i class="fas fa-trash-alt"></i>
    </button>
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
        <a href="#" 
   onclick="event.preventDefault(); document.getElementById('reset-scores-form').submit();" 
   style="
       display: inline-block;
       background-color: {{ $primaryColor }};
       color: white;
       font-family: 'Bebas Neue', sans-serif;
       font-weight: bold;
       padding: 10px 20px;
       border-radius: 50px;
       cursor: pointer;
       transition: background-color 0.3s ease, transform 0.2s ease;
       letter-spacing: 1px;
       text-decoration: none;
       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
   "
   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'; this.style.transform='scale(1.05)';"
   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'; this.style.transform='scale(1)';">
    {{ __('messages.reset') }}
</a>

            <form id="reset-scores-form" action="{{ route('reset.scores') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        @endauth
    </main>


<!-- Modal for Edit Team -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeamModalLabel">{{ __('messages.edit_team') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" id="editTeamForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="team_name" class="form-label">{{ __('messages.team_name') }}</label>
                        <input type="text" name="name" id="team_name" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="logo_path" class="form-label">{{ __('messages.team_logo') }}</label>
                        <input type="file" name="logo_path" id="logo_path" class="form-control">
                        <img id="team_logo_preview" class="img-thumbnail mt-2" style="height: 50px;" alt="">
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

<script>
function openEditTeamModal(teamId, teamName, logoPath) {
    // Utilisez l'URL correcte avec des tirets
    document.getElementById('editTeamForm').action = `/manage-teams/${teamId}`;
    
    // Mettre à jour les champs du modal avec les données de l'équipe
    document.getElementById('team_name').value = teamName;
    document.getElementById('team_logo_preview').src = logoPath || '';

    // Ouvrir le modal
    var modal = new bootstrap.Modal(document.getElementById('editTeamModal'));
    modal.show();
}

$(document).ready(function () {
    /**
     * Met à jour le tableau des matchs via AJAX
     */
    function updateCalendar() {
        const teamFilter = $('#team_filter').val();
        const dateFilter = $('#date_filter').val();

        $.ajax({
            url: '/calendar', // URL pour récupérer les données
            type: 'GET',
            data: {
                team_filter: teamFilter,
                date_filter: dateFilter,
            },
            success: function (response) {
                console.log("Réponse AJAX reçue:", response);

                // Vide le tableau des matchs existant
                const tbodyGames = $('table#games tbody');
                tbodyGames.empty();

                // Vérifie s'il y a des matchs dans la réponse
                if (response.games && response.games.length > 0) {
                    response.games.forEach(game => {
                        const homeTeamLogo = game.home_team?.logo_path
                            ? `<img src="/storage/${game.home_team.logo_path}" class="club-logo" alt="${game.home_team.name}">`
                            : '';
                        const awayTeamLogo = game.away_team?.logo_path
                            ? `<img src="/storage/${game.away_team.logo_path}" class="club-logo" alt="${game.away_team.name}">`
                            : '';

                        // Ajoute une ligne au tableau des matchs
                        let actionsColumn = ''; // Initialisation vide de la colonne Actions

                        // Si l'utilisateur est authentifié, ajoute la colonne Actions
                        if (response.isAuthenticated) {
                            actionsColumn = `
                                <td>
                                    <!-- Icone Edit -->
                                    <button type="button" class="edit-game-btn w-4 mr-2 transform hover:text-blue-500 hover:scale-110"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editGameModal"
                                        data-game-id="${game.id}"
                                        data-match-date="${game.match_date}"
                                        data-home-team-id="${game.home_team.id}"
                                        data-away-team-id="${game.away_team.id}"
                                        data-home-score="${game.home_score}"
                                        data-away-score="${game.away_score}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Icone Trash -->
                                    <form class="delete-game-form" action="/games/${game.id}" method="POST" style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="${response.csrf_token}">
                                        <button type="submit" class="delete-game-btn w-4 mr-2 transform hover:text-red-500 hover:scale-110"
                                            onclick="return confirm('Are you sure you want to delete this game?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            `;
                        }

                        // Ajoute une ligne de match avec la colonne "Score" entre "Home" et "Away"
                        tbodyGames.append(`
                            <tr data-game-id="${game.id}">
                                <td>${new Date(game.match_date).toLocaleDateString()}</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        ${homeTeamLogo} ${game.home_team.name}
                                    </div>
                                </td>
                                <td>
                                    ${response.isAuthenticated
                                        ? `<form class="update-score-form" action="/games/${game.id}/scores" method="POST" style="display: flex; align-items: center;">
                                               <input type="hidden" name="_token" value="${response.csrf_token}">
                                               <input type="number" name="home_team_score" value="${game.home_score ?? ''}" min="0" class="form-control score-input me-1" style="width: 60px;" required>
                                               <span class="mx-1">-</span>
                                               <input type="number" name="away_team_score" value="${game.away_score ?? ''}" min="0" class="form-control score-input me-1" style="width: 60px;" required>
                                               <button type="submit" class="btn btn-sm btn-success">OK</button>
                                           </form>`
                                        : `${game.home_score !== null && game.away_score !== null ? `${game.home_score} - ${game.away_score}` : '---'}`}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        ${awayTeamLogo} ${game.away_team.name}
                                    </div>
                                </td>
                                ${actionsColumn} <!-- Colonne Actions conditionnée -->
                            </tr>
                        `);
                    });
                } else {
                    tbodyGames.append(`
                        <tr>
                            <td colspan="5" class="text-center text-gray-600">
                                ${response.no_games_message || 'No games available.'}
                            </td>
                        </tr>
                    `);
                }

                // Réinitialiser les événements pour les nouveaux éléments
                initializeEvents();
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                alert('Une erreur est survenue lors du chargement des données.');
            }
        });
    }

    /**
     * Initialise les événements pour les boutons et formulaires
     */
    function initializeEvents() {
        // Soumission des scores via AJAX
        $(document).off('submit', '.update-score-form').on('submit', '.update-score-form', function (e) {
            e.preventDefault();

            const form = $(this);
            const actionUrl = form.attr('action');
            const formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Mise à jour dynamique de la ligne de match après l'update du score
                    const updatedGameRow = `
                        <td>${new Date(response.game.match_date).toLocaleDateString()}</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <img src="/storage/${response.game.home_team.logo_path}" class="club-logo" alt="${response.game.home_team.name}"> ${response.game.home_team.name}
                            </div>
                        </td>
                        <td>
                            ${response.game.home_score} - ${response.game.away_score}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <img src="/storage/${response.game.away_team.logo_path}" class="club-logo" alt="${response.game.away_team.name}"> ${response.game.away_team.name}
                            </div>
                        </td>
                        ${response.isAuthenticated ? `
                            <td>
                                <button type="button" class="edit-game-btn w-4 mr-2 transform hover:text-blue-500 hover:scale-110"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editGameModal"
                                    data-game-id="${response.game.id}"
                                    data-match-date="${response.game.match_date}"
                                    data-home-team-id="${response.game.home_team.id}"
                                    data-home-score="${response.game.home_score}"
                                    data-away-score="${response.game.away_score}"
                                    data-away-team-id="${response.game.away_team.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form class="delete-game-form" action="/games/${response.game.id}" method="POST" style="display:inline;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="${response.csrf_token}">
                                    <button type="submit" class="delete-game-btn w-4 mr-2 transform hover:text-red-500 hover:scale-110"
                                        onclick="return confirm('Are you sure you want to delete this game?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        ` : ''}
                    `;

                    // Remplacer la ligne de match dans le tableau
                    $(`tr[data-game-id="${response.game.id}"]`).html(updatedGameRow);

                    // Affiche une notification de succès
                    alert('Scores updated successfully!');
                },
                error: function (xhr, status, error) {
                    console.error("Erreur lors de la mise à jour des scores:", error);
                    alert('Une erreur est survenue lors de la mise à jour des scores.');
                }
            });
        });

        // Boutons Edit
        $(document).off('click', '.edit-game-btn').on('click', '.edit-game-btn', function () {
            const gameId = $(this).data('game-id');
            const matchDate = $(this).data('match-date');
            const homeTeamId = $(this).data('home-team-id');
            const homeScore = $(this).data('home-score');
            const awayScore = $(this).data('away-score');
            const awayTeamId = $(this).data('away-team-id');

            // Pré-remplit le modal avec les données du match
            $('#editGameModal #match_date').val(matchDate);
            $('#editGameModal #home_team_id').val(homeTeamId);
            $('#editGameModal #home_score').val(homeScore);
            $('#editGameModal #away_score').val(awayScore);
            $('#editGameModal #away_team_id').val(awayTeamId);

            // Met à jour l'action du formulaire
            $('#editGameForm').attr('action', `/games/${gameId}`);
        });

        // Boutons Delete
        $(document).off('submit', '.delete-game-form').on('submit', '.delete-game-form', function (e) {
            e.preventDefault();

            const form = $(this);
            if (confirm('Are you sure you want to delete this game?')) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function () {
                        alert('Game deleted successfully!');
                        updateCalendar(); // Met à jour le tableau après suppression
                    },
                    error: function (xhr, status, error) {
                        console.error('Erreur lors de la suppression:', error);
                        alert('Erreur lors de la suppression.');
                    }
                });
            }
        });
    }

    // Attache les événements de changement pour les filtres
    $('#team_filter').on('change', updateCalendar);
    $('#date_filter').on('change', updateCalendar);

    // Initialise les événements au chargement
    initializeEvents();
});


</script>

@if($games->isNotEmpty())
    @foreach($games as $game)
        <!--  modal Edit Game -->
        <div class="modal fade" id="editGameModal" tabindex="-1" aria-labelledby="editGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGameModalLabel">Edit Match</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGameForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="match_date" class="form-label">Match Date</label>
                        <input type="date" name="match_date" id="match_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="home_team_id" class="form-label">Home Team</label>
                        <select name="home_team_id" id="home_team_id" class="form-select" required>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="away_team_id" class="form-label">Away Team</label>
                        <select name="away_team_id" id="away_team_id" class="form-select" required>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="home_score" class="form-label">Home Score</label>
                        <input type="number" name="home_score" id="home_score" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="away_score" class="form-label">Away Score</label>
                        <input type="number" name="away_score" id="away_score" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
    @endforeach
@endif
                            
    <x-footerforhome />
</body>
</html>
