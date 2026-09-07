<?php

namespace App\Http\Controllers;

use App\Models\PlayerU21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlayerU21Controller extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PlayersU21/Index', [
            'players' => PlayerU21::orderBy('number', 'asc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PlayersU21/Form', [
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
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        PlayerU21::create($data);

        return redirect()->route('playersu21.index')->with('success', 'Joueur U21 ajouté.');
    }

    public function edit(PlayerU21 $playersu21)
    {
        return Inertia::render('Admin/PlayersU21/Form', [
            'player' => $playersu21,
        ]);
    }

    public function update(Request $request, PlayerU21 $playersu21)
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
        ]);

        if ($request->hasFile('photo')) {
            if ($playersu21->photo) {
                Storage::disk('public')->delete($playersu21->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $playersu21->update($data);

        return redirect()->route('playersu21.index')->with('success', 'Joueur U21 mis à jour.');
    }

    public function destroy(PlayerU21 $playersu21)
    {
        if ($playersu21->photo) {
            Storage::disk('public')->delete($playersu21->photo);
        }

        $playersu21->delete();

        return redirect()->route('playersu21.index')->with('success', 'Joueur U21 supprimé.');
    }
}
