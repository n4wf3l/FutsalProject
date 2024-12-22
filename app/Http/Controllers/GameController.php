<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Championship;
use App\Models\BackgroundImage;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function showCalendar(Request $request)
    {
        $championship = Championship::first();
        $siteSetting = SiteSetting::first();
        $clubName = $siteSetting->club_name ?? 'Default Club Name';
        $team = Team::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($clubName))])->first();
    
        if (!$team) {
            $teamId = 1;
            $teamNotFound = false;
        } else {
            $teamId = $team->id;
            $teamNotFound = false;
        }
    
        $teamFilter = $request->input('team_filter', 'club');
        $dateFilter = $request->input('date_filter', 'upcoming');
        $today = now()->startOfDay();
    
        $gamesQuery = Game::with(['homeTeam', 'awayTeam', 'updatedBy']);
    
        if ($teamFilter === 'club' && $teamId) {
            $gamesQuery->where(function ($query) use ($teamId) {
                $query->where('home_team_id', $teamId)
                      ->orWhere('away_team_id', $teamId);
            });
        }
    
        if ($dateFilter === 'upcoming') {
            $gamesQuery->where('match_date', '>=', $today);
        } elseif ($dateFilter === 'results') {
            $gamesQuery->where('match_date', '<', $today);
        }
    
        // Appliquer l'ordre décroissant sur la date
        $games = $gamesQuery->orderBy('match_date', 'desc')->get(); // Tri par date décroissante
    
        $lastUpdatedGame = Game::with('updatedBy')->orderBy('updated_at', 'desc')->first();
    
        $teams = Team::orderBy('points', 'desc')
                     ->orderBy('goal_difference', 'desc')
                     ->orderBy('goals_for', 'desc')
                     ->get();
        
        if ($request->ajax()) {
            return response()->json([
                'games' => $games,
                'teams' => $teams, // Inclure le classement des équipes dans la réponse
                'isAuthenticated' => Auth::check(),
                'csrf_token' => csrf_token(),
            ], 200); // Code HTTP 200 pour succès
        }
    
        $backgroundImage = BackgroundImage::where('assigned_page', 'calendar')->latest()->first();
    
        return view('calendar', compact(
            'championship',
            'games',
            'teams',
            'backgroundImage',
            'clubName',
            'teamId',
            'teamFilter',
            'dateFilter',
            'lastUpdatedGame',
            'teamNotFound'
        ));
    }
    
    // Méthode pour l'API - afficher le calendrier et les équipes
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

    // Méthode pour mettre à jour les scores d'un match
    public function updateScores(Request $request, Game $game)
    {
        $request->validate([
            'home_team_score' => 'required|integer|min:0',
            'away_team_score' => 'required|integer|min:0',
        ]);
    
        // Réinitialiser les statistiques de l'ancien score
        $this->resetTeamStats($game);
    
        // Mettre à jour les scores
        $game->home_score = $request->input('home_team_score');
        $game->away_score = $request->input('away_team_score');
        $game->updated_by_user_id = Auth::id();
        $game->save();
    
        // Mettre à jour les statistiques des équipes
        $this->updateTeamStats($game);
    
        // Retourner une réponse JSON pour les requêtes AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Scores updated successfully.',
                'home_score' => $game->home_score,
                'away_score' => $game->away_score,
            ]);
        }
    
        // Sinon, rediriger l'utilisateur (non AJAX)
        return redirect()->route('calendar.show')->with('success', 'Scores updated successfully.');
    }

    // API pour mettre à jour les scores d'un match
    public function apiUpdateScores(Request $request, Game $game)
    {
        $request->validate([
            'home_team_score' => 'required|integer|min:0',
            'away_team_score' => 'required|integer|min:0',
        ]);

        $game->home_score = $request->input('home_team_score');
        $game->away_score = $request->input('away_team_score');
        $game->save();

        $this->updateTeamStats($game);

        return response()->json(['message' => 'Scores updated successfully'], 200);
    }

    // Mise à jour des statistiques des équipes après un match
    private function updateTeamStats(Game $game)
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        // Mettre à jour les buts marqués et encaissés
        $homeTeam->goals_for += $game->home_score;
        $homeTeam->goals_against += $game->away_score;
        $awayTeam->goals_for += $game->away_score;
        $awayTeam->goals_against += $game->home_score;

        // Calculer la différence de buts
        $homeTeam->goal_difference = $homeTeam->goals_for - $homeTeam->goals_against;
        $awayTeam->goal_difference = $awayTeam->goals_for - $awayTeam->goals_against;

        // Mettre à jour les points en fonction du résultat
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

        // Mettre à jour le nombre de matchs joués
        $homeTeam->games_played += 1;
        $awayTeam->games_played += 1;

        $homeTeam->save();
        $awayTeam->save();
    }

    // Réinitialiser les statistiques des équipes pour un match donné
    private function resetTeamStats(Game $game)
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        if (!is_null($game->home_score) && !is_null($game->away_score)) {
            // Réinitialiser les scores précédents
            $homeTeam->goals_for -= $game->home_score;
            $homeTeam->goals_against -= $game->away_score;
            $awayTeam->goals_for -= $game->away_score;
            $awayTeam->goals_against -= $game->home_score;

            // Réinitialiser les points et résultats précédents
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

            // Réinitialiser les matchs joués
            $homeTeam->games_played -= 1;
            $awayTeam->games_played -= 1;

            $homeTeam->save();
            $awayTeam->save();
        }
    }

    // Méthode pour afficher la liste des matchs (Vue)
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        return view('games.index', compact('games'));
    }

    // Afficher le formulaire de création d'un match
    public function create()
    {
        $teams = Team::all();
        return view('games.create', compact('teams'));
    }

    // Enregistrer un nouveau match
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

    // Afficher le formulaire d'édition d'un match
    public function edit(Game $game)
    {
        $teams = Team::all();
        return view('games.edit', compact('game', 'teams'));
    }

    // Mettre à jour un match existant
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

    // Supprimer un match
    public function destroy(Game $game)
    {
        // Réinitialiser les statistiques avant de supprimer le match
        $this->resetTeamStats($game);
        $game->delete();

        return redirect()->route('calendar.show')->with('success', 'Game and its scores deleted successfully.');
    }

    // Réinitialiser les scores de tous les matchs
    public function resetScores()
    {
        Game::query()->update(['home_score' => null, 'away_score' => null]);

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

    // Enregistrer plusieurs matchs à la fois
    public function storeMultiple(Request $request)
    {
        $matches = $request->input('matches', []);

        foreach ($matches as $match) {
            if (!$match['home_team_id'] || !$match['away_team_id']) {
                return redirect()->back()->withErrors(['Please select both a home team and an away team.']);
            }

            if ($match['home_team_id'] === $match['away_team_id']) {
                return redirect()->back()->withErrors(['A team cannot play against itself.']);
            }

            if (!$match['match_date']) {
                return redirect()->back()->withErrors(['Please select a date for the match.']);
            }

            Game::create([
                'home_team_id' => $match['home_team_id'],
                'away_team_id' => $match['away_team_id'],
                'match_date' => $match['match_date'],
            ]);
        }

        return redirect()->route('calendar.show')->with('success', 'Matches created successfully.');
    }

    // Créer ou mettre à jour le championnat
    public function storeChampionship(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'season' => 'required|string|max:255',
        ]);

        $championship = Championship::first();

        if ($championship) {
            $championship->update([
                'name' => $request->input('name'),
                'season' => $request->input('season'),
            ]);
            return redirect()->route('calendar.show')->with('success', 'Championship updated successfully.');
        } else {
            Championship::create([
                'name' => $request->input('name'),
                'season' => $request->input('season'),
            ]);
            return redirect()->route('calendar.show')->with('success', 'Championship created successfully.');
        }
    }
}
