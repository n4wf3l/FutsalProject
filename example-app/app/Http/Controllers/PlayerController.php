<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Coach;
use App\Models\Championship;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();
        $staff = Staff::all(); 
        $coach = Coach::first(); // Supposons que vous n'avez qu'un seul coach principal
        $championship = Championship::first(); 
        // Passer les données à la vue
        return view('teams', compact('players', 'staff', 'coach', 'championship'));
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

    public function destroy(PlayerU21 $playerU21)
{
    \Log::info('Received request to delete Player ID:', [$playerU21 ? $playerU21->id : 'null']);

    if ($playerU21->photo) {
        \Log::info('Deleting photo for Player ID:', [$playerU21->id]);
        Storage::disk('public')->delete($playerU21->photo);
    }

    $playerU21->delete();

    \Log::info('Player deleted successfully:', [$playerU21->id]);

    return redirect()->route('playersu21.index')->with('success', 'U21 Player deleted successfully.');
}
}
