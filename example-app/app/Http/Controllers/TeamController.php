<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Afficher la liste des équipes (Vue d'administration)
    public function index()
    {
        $teams = Team::orderBy('name')->get();
        return view('manage_teams.index', compact('teams'));
    }

    // Afficher le formulaire pour créer une nouvelle équipe (Vue d'administration)
    public function create()
    {
        return view('manage_teams.create');
    }

    // Enregistrer une nouvelle équipe (Vue d'administration)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'logo' => 'nullable|image|max:2048', // optionnel, mais si fourni, doit être une image
        ]);

        // Gérer le téléversement du logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Team::create([
            'name' => $request->input('name'),
            'logo_path' => $logoPath, // Changement ici pour correspondre à la base de données
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
        ]);

        return redirect()->route('calendar.show')->with('success', 'Team created successfully.');
    }

    // Afficher les détails d'une équipe spécifique (Vue d'administration)
    public function show(Team $team)
    {
        return view('manage_teams.show', compact('team'));
    }

    // Afficher le formulaire pour éditer une équipe existante (Vue d'administration)
    public function edit(Team $team)
    {
        return view('manage_teams.edit', compact('team'));
    }

    // Mettre à jour une équipe existante (Vue d'administration)
    public function update(Request $request, Team $team)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $team->name = $request->name;

    if ($request->hasFile('logo_path')) {
        // Supprimer l'ancien logo s'il existe
        if ($team->logo_path) {
            Storage::delete($team->logo_path);
        }

        // Enregistrer le nouveau logo
        $path = $request->file('logo_path')->store('logos');
        $team->logo_path = $path;
    }

    $team->save();

    return redirect()->route('calendar.show')->with('success', 'Team updated successfully.');
}

    // Supprimer une équipe (Vue d'administration)
    public function destroy(Team $team)
    {
        // Supprimer le logo associé si nécessaire
        if ($team->logo_path) {
            \Storage::disk('public')->delete($team->logo_path);
        }
    
        $team->delete();
    
        // Rediriger vers la page du calendrier après la suppression
        return redirect()->route('calendar.show')->with('success', 'Team deleted successfully.');
    }
}
