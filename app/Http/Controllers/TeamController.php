<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Teams/Index', [
            'teams' => Team::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Teams/Form', ['team' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Team::create([
            'name' => $data['name'],
            'logo_path' => $logoPath,
            'points' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
        ]);

        return redirect()->route('manage_teams.index')->with('success', 'Équipe ajoutée.');
    }

    public function edit(Team $team)
    {
        return Inertia::render('Admin/Teams/Form', ['team' => $team]);
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $team->name = $data['name'];

        if ($request->hasFile('logo')) {
            if ($team->logo_path) {
                Storage::disk('public')->delete($team->logo_path);
            }
            $team->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $team->save();

        return redirect()->route('manage_teams.index')->with('success', 'Équipe mise à jour.');
    }

    public function destroy(Team $team)
    {
        if ($team->logo_path) {
            Storage::disk('public')->delete($team->logo_path);
        }
        $team->delete();
        return redirect()->route('manage_teams.index')->with('success', 'Équipe supprimée.');
    }
}
