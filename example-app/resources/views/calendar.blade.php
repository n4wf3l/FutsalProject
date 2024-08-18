<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | {{ $clubName }}</title>
    @if($logoPath)
        <link rel="icon" href="{{ $logoPath }}" type="image/png"> <!-- Type de l'image selon le type du logo -->
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
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
            margin-bottom: 20px;
        }
        .admin-buttons a {
            display: inline-block;
            background-color: {{ $secondaryColor }};
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
        }
        .admin-buttons a:hover {
            background-color: darken({{ $secondaryColor }}, 10%);
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
    </style>
</head>
<body class="bg-gray-100">
    <x-navbar />
    <header class="text-center my-12" style="margin-top: 20px;">
        <h1 class="text-6xl font-bold text-gray-900" style="font-size:60px;">Classement Futsal D1</h1>
        <div class="flex justify-center items-center mt-4">
            <p class="text-xl text-gray-600" style="margin-bottom: 20px;">Discover additional information by hovering with your mouse.</p>
        </div>
    </header>

    <main class="py-12">
        <h2 class="calendar-title">Classement - Dina Kénitra</h2>

        @auth
<div class="admin-buttons">
    <a href="{{ route('manage_teams.create') }}" class="btn btn-primary">Ajouter une équipe</a>
    <a href="{{ route('games.create') }}" class="btn btn-primary">Créer un match</a>
    <a href="#" 
   onclick="event.preventDefault(); confirmReset();" 
   class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700">
   Reset Scores
</a>

<form id="reset-scores-form" action="{{ route('reset.scores') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function confirmReset() {
    // Premier message d'alerte
    let firstConfirmation = confirm("This action will reset all scores and standings. Are you sure you want to proceed?");
    
    if (firstConfirmation) {
        // Deuxième message d'alerte
        let secondConfirmation = confirm("This is your final confirmation. Are you absolutely sure?");
        
        if (secondConfirmation) {
            // Soumettre le formulaire si les deux confirmations sont positives
            document.getElementById('reset-scores-form').submit();
        }
    }
}
</script>
</div>
@endauth

        <div class="container table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Club</th>
                <th>Pts</th>
                <th>Jo</th>
                <th>G</th>
                <th>N</th>
                <th>P</th>
                <th>Diff</th>
                <th>Actions</th> <!-- Nouvelle colonne pour les actions -->
            </tr>
        </thead>
        <tbody>
            <!-- Classement des clubs -->
            @foreach($teams as $team)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="club-name">
                    <!-- Affichage du logo -->
                    @if($team->logo_path)
                        <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }} Logo" style="height: 24px; margin-right: 10px;">
                    @endif
                    <span>{{ $team->name }}</span>
                </td>
                <td>{{ $team->points }}</td>
                <td>{{ $team->games_played }}</td> <!-- Affichage des matches joués -->
                <td>{{ $team->wins }}</td>
                <td>{{ $team->draws }}</td>
                <td>{{ $team->losses }}</td>
                <td>{{ $team->goal_difference }}</td>
                <td>
                    <!-- Formulaire pour supprimer une équipe -->
                    <form action="{{ route('manage_teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('Are you sure you want to delete this team?');">
                            X
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <h2 class="calendar-title">Calendrier des Matchs - Dina Kénitra</h2>
        <div class="container table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Domicile</th>
                        <th>Extérieur</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                    <tr>
                        <td>{{ $game->match_date }}</td>
                        <td>{{ $game->match_time }}</td>
                        <td>{{ $game->homeTeam->name }}</td>
                        <td>{{ $game->awayTeam->name }}</td>
                        <td>
                            @auth
                            <form action="{{ route('games.updateScores', $game->id) }}" method="POST">
                                @csrf
                                <input type="number" name="home_team_score" value="{{ old('home_team_score', $game->home_score) }}" min="0" class="score-input">
                                <span> - </span>
                                <input type="number" name="away_team_score" value="{{ old('away_team_score', $game->away_score) }}" min="0" class="score-input">
                                <button type="submit" class="update-scores-button">Update Scores</button>
                            </form>
                            @else
                            {{ $game->home_score }} - {{ $game->away_score }}
                            @endauth
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <x-footer />
</body>
</html>
