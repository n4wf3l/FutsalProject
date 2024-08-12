<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class PlayerController extends Controller
{
    public function index()
    {
        // Récupérer tous les joueurs
        $players = Player::all();
        $staff = Staff::all(); 
        // Passer les joueurs à la vue
        return view('teams', compact('players', 'staff'));
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
            'contract_until' => 'required|date',
        ]);

        $player = new Player($request->all());

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $player->photo = $path;
        }

        $player->save();

        return redirect()->route('players.index')->with('success', 'Player added successfully');
    }
    public function edit(Player $player)
    {
        $userSettings = Auth::user()->userSettings; // Récupère les paramètres utilisateur pour la personnalisation
    
        return view('players.edit', compact('player', 'userSettings'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
            'contract_until' => 'required|date',
        ]);

        $player->fill($request->all());

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $player->photo = $path;
        }

        $player->save();

        return redirect()->route('players.index')->with('success', 'Player updated successfully');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Player deleted successfully');
    }
}
