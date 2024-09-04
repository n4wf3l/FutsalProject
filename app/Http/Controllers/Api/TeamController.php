<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Afficher la liste des équipes
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams, 200);
    }

    // Afficher les détails d'une équipe spécifique
    public function show(Team $team)
    {
        return response()->json($team, 200);
    }

    // Enregistrer une nouvelle équipe
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'logo' => 'nullable|image|max:2048', // optionnel, mais si fourni, doit être une image
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $team = Team::create([
            'name' => $request->input('name'),
            'logo_path' => $logoPath, // Changement ici pour correspondre à la base de données
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
        ]);

        return response()->json($team, 201);
    }

    // Mettre à jour une équipe existante
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($team->logo_path) {
                \Storage::disk('public')->delete($team->logo_path);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $team->logo_path = $logoPath;
        }

        $team->name = $request->input('name');
        $team->save();

        return response()->json($team, 200);
    }

    // Supprimer une équipe
    public function destroy(Team $team)
    {
        // Supprimer le logo associé si nécessaire
        if ($team->logo_path) {
            \Storage::disk('public')->delete($team->logo_path);
        }
    
        $team->delete();
    
        // Retourner une réponse JSON avec un message de succès
        return response()->json(['message' => 'Team deleted successfully', 'redirect' => route('calendar.show')], 200);
    }
}
