<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Championship;

class GameController extends Controller
{
    // Afficher le calendrier et le classement (Vue)
    public function showCalendar()
    {
        // Récupérer les informations du championnat
        $championship = Championship::first();
    
        // Vérifier si un championnat existe
        if (!$championship) {
            return redirect()->back()->with('error', 'No championship data available.');
        }
    
        // Récupérer les matchs avec les relations des équipes à domicile et à l'extérieur
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
    
        // Récupérer la liste des équipes triées par points, différence de buts et buts marqués
        $teams = Team::orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->orderBy('goals_for', 'desc')
            ->get();
    
        // Passer les données du championnat, des matchs et des équipes à la vue
        return view('calendar', compact('championship', 'games', 'teams'));
    }

    public function storeChampionship(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'season' => 'required|string|max:255',
    ]);

    // Créez ou mettez à jour le championnat
    $championship = Championship::updateOrCreate(
        ['id' => 1], // Ou la logique que vous souhaitez pour identifier un championnat
        [
            'name' => $request->input('name'),
            'season' => $request->input('season'),
        ]
    );

    return redirect()->route('calendar.show')->with('success', 'Championship settings saved successfully.');
}

    // Afficher le calendrier et le classement (API)
    public function apiShowCalendar()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();

        $teams = Team::orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->orderBy('goals_for', 'desc')
            ->get();

        return response()->json([
            'games' => $games,
            'teams' => $teams
        ], 200);
    }

    // Mettre à jour les scores d'un match (Vue)
    public function updateScores(Request $request, Game $game)
    {
        $request->validate([
            'home_team_score' => 'required|integer|min:0',
            'away_team_score' => 'required|integer|min:0',
        ]);
    
        // Réinitialiser les statistiques liées à l'ancien score
        $this->resetTeamStats($game);
    
        // Mettre à jour le score du match
        $game->home_score = $request->input('home_team_score');
        $game->away_score = $request->input('away_team_score');
        $game->save();
    
        // Appliquer les nouvelles statistiques après la mise à jour des scores
        $this->updateTeamStats($game);
    
        return redirect()->route('calendar.show')->with('success', 'Scores updated successfully.');
    }

    // Mettre à jour les scores d'un match (API)
    public function apiUpdateScores(Request $request, Game $game)
    {
        $request->validate([
            'home_team_score' => 'required|integer|min=0',
            'away_team_score' => 'required|integer|min=0',
        ]);

        $game->home_team_score = $request->input('home_team_score');
        $game->away_team_score = $request->input('away_team_score');
        $game->save();

        $this->updateTeamStats($game);

        return response()->json(['message' => 'Scores updated successfully'], 200);
    }

    private function updateTeamStats(Game $game)
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;
    
        // Appliquer les nouveaux scores
        $homeTeam->goals_for += $game->home_score;
        $homeTeam->goals_against += $game->away_score;
        $awayTeam->goals_for += $game->away_score;
        $awayTeam->goals_against += $game->home_score;
    
        // Calcul de la différence de buts
        $homeTeam->goal_difference = $homeTeam->goals_for - $homeTeam->goals_against;
        $awayTeam->goal_difference = $awayTeam->goals_for - $awayTeam->goals_against;
    
        // Attribution des points en fonction du score
        if ($game->home_score > $game->away_score) {
            $homeTeam->points += 3;
            $homeTeam->wins += 1;
            $awayTeam->losses += 1;
        } elseif ($game->home_score < $game->away_score) {
            $awayTeam->points += 3;
            $awayTeam->wins += 1;
            $homeTeam->losses += 1;
        } else {
            $homeTeam->points += 1;
            $awayTeam->points += 1;
            $homeTeam->draws += 1;
            $awayTeam->draws += 1;
        }
    
        // Mettre à jour les matchs joués
        $homeTeam->games_played += 1;
        $awayTeam->games_played += 1;
    
        $homeTeam->save();
        $awayTeam->save();
    }
    
    private function resetTeamStats(Game $game)
{
    $homeTeam = $game->homeTeam;
    $awayTeam = $game->awayTeam;

    // Si le score était déjà défini, annuler les effets des statistiques précédentes
    if (!is_null($game->home_score) && !is_null($game->away_score)) {
        // Annuler les effets des buts
        $homeTeam->goals_for -= $game->home_score;
        $homeTeam->goals_against -= $game->away_score;
        $awayTeam->goals_for -= $game->away_score;
        $awayTeam->goals_against -= $game->home_score;

        // Annuler les effets de la différence de buts
        $homeTeam->goal_difference = $homeTeam->goals_for - $homeTeam->goals_against;
        $awayTeam->goal_difference = $awayTeam->goals_for - $awayTeam->goals_against;

        // Annuler les points gagnés/perdus
        if ($game->home_score > $game->away_score) {
            $homeTeam->points -= 3;
            $homeTeam->wins -= 1;
            $awayTeam->losses -= 1;
        } elseif ($game->home_score < $game->away_score) {
            $awayTeam->points -= 3;
            $awayTeam->wins -= 1;
            $homeTeam->losses -= 1;
        } else {
            $homeTeam->points -= 1;
            $awayTeam->points -= 1;
            $homeTeam->draws -= 1;
            $awayTeam->draws -= 1;
        }

        // Annuler les matchs joués
        $homeTeam->games_played -= 1;
        $awayTeam->games_played -= 1;

        $homeTeam->save();
        $awayTeam->save();
    }
}
    
    private function applyMatchResult(Team $team, $goals_for, $goals_against)
    {
        $team->goals_for += $goals_for;
        $team->goals_against += $goals_against;
        $team->goal_difference = $team->goals_for - $team->goals_against;
    
        if ($goals_for > $goals_against) {
            $team->wins += 1;
            $team->points += 3;
        } elseif ($goals_for == $goals_against) {
            $team->draws += 1;
            $team->points += 1;
        } else {
            $team->losses += 1;
        }
    
        $team->games_played += 1;
    }

    // Afficher la liste des matches (Vue)
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        return view('games.index', compact('games'));
    }

    // Afficher la liste des matches (API)
    public function apiIndex()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        return response()->json($games, 200);
    }

    // Afficher le formulaire pour créer un nouveau match (Vue)
    public function create()
    {
        $teams = Team::all();
        return view('games.create', compact('teams'));
    }

    // Enregistrer un nouveau match (Vue)
    public function store(Request $request)
{
    $request->validate([
        'home_team_id' => 'required|exists:teams,id',
        'away_team_id' => 'required|exists:teams,id|different:home_team_id',
        'match_date' => 'required|date', 
    ]);

    Game::create($request->all());

    return redirect()->route('calendar.show')->with('success', 'Game created successfully.');
}

    // Enregistrer un nouveau match (API)
    public function apiStore(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date', // Assurez-vous que la date est fournie
        ]);

        $game = Game::create($request->all());

        return response()->json($game, 201);
    }

    // Afficher les détails d'un match spécifique (Vue)
    public function show(Game $game)
    {
        return view('games.show', compact('game'));
    }

    // Afficher les détails d'un match spécifique (API)
    public function apiShow(Game $game)
    {
        return response()->json($game, 200);
    }

    // Afficher le formulaire pour éditer un match existant (Vue)
    public function edit(Game $game)
    {
        $teams = Team::all();
        return view('games.edit', compact('game', 'teams'));
    }

    // Mettre à jour un match existant (Vue)
    public function update(Request $request, Game $game)
{
    $request->validate([
        'home_team_id' => 'required|exists:teams,id|different:away_team_id',
        'away_team_id' => 'required|exists:teams,id|different:home_team_id',
        'match_date' => 'required|date',
    ]);

    $game->update($request->all());

    return redirect()->route('calendar.show')->with('success', 'Game updated successfully.');
}

    // Mettre à jour un match existant (API)
    public function apiUpdate(Request $request, Game $game)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date', // Assurez-vous que la date est fournie
        ]);

        $game->update($request->all());

        $this->updateTeamStats($game);

        return response()->json($game, 200);
    }

    // Supprimer un match (Vue)
    public function destroy(Game $game)
{
    // Récupérer les équipes impliquées
    $homeTeam = $game->homeTeam;
    $awayTeam = $game->awayTeam;

    // Réduire les scores et ajuster les statistiques des équipes
    if ($game->home_score !== null && $game->away_score !== null) {
        // Réduire les buts marqués et encaissés
        $homeTeam->goals_for -= $game->home_score;
        $homeTeam->goals_against -= $game->away_score;
        $awayTeam->goals_for -= $game->away_score;
        $awayTeam->goals_against -= $game->home_score;

        // Ajuster le goal difference
        $homeTeam->goal_difference = $homeTeam->goals_for - $homeTeam->goals_against;
        $awayTeam->goal_difference = $awayTeam->goals_for - $awayTeam->goals_against;

        // Ajuster les points, victoires, défaites, et nuls
        if ($game->home_score > $game->away_score) {
            // Domicile a gagné, extérieur a perdu
            $homeTeam->points -= 3;
            $homeTeam->wins -= 1;
            $awayTeam->losses -= 1;
        } elseif ($game->home_score < $game->away_score) {
            // Extérieur a gagné, domicile a perdu
            $awayTeam->points -= 3;
            $awayTeam->wins -= 1;
            $homeTeam->losses -= 1;
        } else {
            // Match nul
            $homeTeam->points -= 1;
            $awayTeam->points -= 1;
            $homeTeam->draws -= 1;
            $awayTeam->draws -= 1;
        }

        // Réduire le nombre de matchs joués
        $homeTeam->games_played -= 1;
        $awayTeam->games_played -= 1;

        // Enregistrer les changements
        $homeTeam->save();
        $awayTeam->save();
    }

    // Supprimer le match
    $game->delete();

    // Redirection avec message de succès
    return redirect()->route('calendar.show')->with('success', 'Game and its scores deleted successfully.');
}

    // Supprimer un match (API)
    public function apiDestroy(Game $game)
    {
        $game->delete();
        return response()->json(null, 204);
    }

    public function resetScores()
{
    // Réinitialiser les scores des matchs
    Game::query()->update(['home_score' => null, 'away_score' => null]);

    // Réinitialiser les statistiques des équipes
    Team::query()->update([
        'points' => 0,
        'wins' => 0,
        'draws' => 0,
        'losses' => 0,
        'goals_for' => 0,
        'goals_against' => 0,
        'goal_difference' => 0,
        'games_played' => 0,
    ]);

    return redirect()->route('calendar.show')->with('success', 'All scores and statistics have been reset.');
}

public function storeMultiple(Request $request)
{
    $matches = $request->input('matches', []);

    foreach ($matches as $match) {
        Game::create([
            'home_team_id' => $match['home_team_id'],
            'away_team_id' => $match['away_team_id'],
            'match_date' => $match['match_date'],
        ]);
    }

    return redirect()->route('calendar.show')->with('success', 'Matches created successfully.');
}
}
