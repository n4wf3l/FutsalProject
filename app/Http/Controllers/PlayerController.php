<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Coach;
use App\Models\Championship;

class PlayerController extends Controller
{
    public function publicRoster()
    {
        return Inertia::render('Teams', [
            'players' => Player::orderBy('number', 'asc')->get(),
            'staff' => Staff::all(),
            'coach' => Coach::first(),
            'championship' => Championship::first(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/Players/Index', [
            'players' => Player::orderBy('number', 'asc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Players/Form', [
            'player' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
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

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Player::create($data);

        return redirect()->route('players.index')->with('success', 'Joueur ajouté.');
    }

    public function edit(Player $player)
    {
        return Inertia::render('Admin/Players/Form', [
            'player' => $player,
        ]);
    }

    public function update(Request $request, Player $player)
    {
        $data = $request->validate([
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

        if ($request->hasFile('photo')) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $player->update($data);

        return redirect()->route('players.index')->with('success', 'Joueur mis à jour.');
    }

    public function destroy(Player $player)
    {
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }

        $player->delete();

        return redirect()->route('players.index')->with('success', 'Joueur supprimé.');
    }

    public function dashboard()
    {
        return redirect()->route('dashboard');
    }
}
