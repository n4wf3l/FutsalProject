<?php

namespace App\Http\Controllers;

use App\Models\PlayerU21;
use App\Models\Championship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BackgroundImage;

class PlayerU21Controller extends Controller
{
    // Display a listing of the U21 players
    public function index()
    {
        $championship = Championship::first(); 
        $players = PlayerU21::orderBy('last_name')->get();
        $backgroundImage = BackgroundImage::where('assigned_page', 'teamu21')->latest()->first();
        return view('playersu21.index', compact('players', 'championship', 'backgroundImage'));
    }

    // Show the form for creating a new U21 player
    public function create()
    {
        return view('playersu21.create');
    }

    // Store a newly created U21 player in the database
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        PlayerU21::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'photo' => $photoPath,
            'birthdate' => $request->input('birthdate'),
            'position' => $request->input('position'),
            'number' => $request->input('number'),
            'nationality' => $request->input('nationality'),
            'height' => $request->input('height'),
        ]);

        return redirect()->route('playersu21.index')->with('success', 'U21 Player created successfully.');
    }

    // Show the form for editing a specific U21 player
    public function edit(PlayerU21 $playerU21)
    {
        return view('playersu21.edit', compact('playerU21'));
    }

    // Update the specified U21 player in the database
    public function update(Request $request, $id)
    {
        // Trouver le joueur via l'ID
        $playerU21 = PlayerU21::findOrFail($id);
        
        // Log pour vérifier que le joueur est trouvé
    
        // Mise à jour des champs
        $playerU21->update($request->only([
            'first_name', 'last_name', 'birthdate', 'position', 'number', 'nationality', 'height'
        ]));
    
        // Vérifier après la sauvegarde
    
        return redirect()->route('playersu21.index')->with('success', 'Player updated successfully.');
    }

    // Remove the specified U21 player from the database
    public function destroy($id)
{
    $playerU21 = PlayerU21::find($id);

    if (!$playerU21) {
        \Log::error('Player not found with ID:', [$id]);
        return redirect()->route('playersu21.index')->with('error', 'Player not found.');
    }

    if ($playerU21->photo) {
        \Log::info('Deleting photo for Player ID:', [$playerU21->id]);
        Storage::disk('public')->delete($playerU21->photo);
    }

    $playerU21->delete();

    return redirect()->route('playersu21.index')->with('success', 'U21 Player deleted successfully.');
}
}
