<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Afficher la liste des matches
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        return response()->json($games, 200);
    }

    // Afficher les détails d'un match spécifique
    public function show(Game $game)
    {
        return response()->json($game, 200);
    }

    // Enregistrer un nouveau match
    public function store(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date', // Assurez-vous que la date est fournie
        ]);

        $game = Game::create($request->all());

        return response()->json($game, 201);
    }

    // Mettre à jour un match existant
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date', // Assurez-vous que la date est fournie
        ]);

        $game->update($request->all());

        return response()->json($game, 200);
    }

    // Supprimer un match
    public function destroy(Game $game)
    {
        $game->delete();
        return response()->json(null, 204);
    }
}
