<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Championship;
use App\Models\BackgroundImage;
use App\Models\SiteSetting;

class GameController extends Controller
{
    public function showCalendar(Request $request)
    {
        // Récupérer le premier championnat existant
        $championship = Championship::first();

        // Récupérer les paramètres du site, en particulier le nom du club
        $siteSetting = SiteSetting::first();
        $clubName = $siteSetting->club_name ?? 'Default Club Name'; // Nom par défaut si absent
        $clubNamePrefix = substr($clubName, 0, 4); // Prefixe du nom du club pour filtrer les équipes

        // Filtre par équipe et date
        $teamFilter = $request->input('team_filter', 'all_teams');
        $dateFilter = $request->input('date_filter', 'results_and_upcoming');
        $today = now()->startOfDay();

        // Requête de base pour les matchs
        $gamesQuery = Game::with(['homeTeam', 'awayTeam']);

        // Filtrer par équipe si nécessaire
        if ($teamFilter === 'specific_team') {
            $gamesQuery->where(function ($query) use ($clubNamePrefix) {
                $query->whereHas('homeTeam', function ($q) use ($clubNamePrefix) {
                    $q->where('name', 'like', $clubNamePrefix . '%');
                })->orWhereHas('awayTeam', function ($q) use ($clubNamePrefix) {
                    $q->where('name', 'like', $clubNamePrefix . '%');
                });
            });
        }

        // Filtrer par date (matchs à venir ou résultats)
        if ($dateFilter === 'upcoming') {
            $gamesQuery->where('match_date', '>=', $today)
                       ->whereNull('home_score')
                       ->whereNull('away_score');
        } elseif ($dateFilter === 'results') {
            $gamesQuery->whereNotNull('home_score')
                       ->whereNotNull('away_score');
        }

        // Obtenir les matchs
        $games = $gamesQuery->orderBy('match_date')->get();

        // Récupérer les équipes classées par points, différence de buts et buts marqués
        $teams = Team::orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->orderBy('goals_for', 'desc')
            ->get();

        // Récupérer l'image de fond pour la page du calendrier
        $backgroundImage = BackgroundImage::where('assigned_page', 'calendar')->latest()->first();

        // Retourner la vue avec les données
        return view('calendar', compact('championship', 'games', 'teams', 'backgroundImage', 'clubName', 'clubNamePrefix'));
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
        $game->save();

        // Mettre à jour les statistiques des équipes
        $this->updateTeamStats($game);

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
