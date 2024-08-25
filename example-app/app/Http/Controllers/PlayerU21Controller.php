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
    public function edit(PlayerU21 $playersu21) // Route parameter name should match the model binding
    {
        return view('playersu21.edit', ['playerU21' => $playersu21]);
    }
    
    public function update(Request $request, PlayerU21 $playersu21)
{
    // Log the incoming data
    \Log::info('Update data received:', $request->all());

    // Validate the incoming data
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

    // Check if a photo is being updated
    if ($request->hasFile('photo')) {
        \Log::info('Photo update detected.');

        // Delete the old photo if it exists
        if ($playersu21->photo) {
            Storage::disk('public')->delete($playersu21->photo);
        }

        // Store the new photo
        $photoPath = $request->file('photo')->store('photos', 'public');
        $playersu21->photo = $photoPath;
    }

    // Update the player with the new data
    $playersu21->update($request->only([
        'first_name', 'last_name', 'birthdate', 'position', 'number', 'nationality', 'height'
    ]));

    // Log to confirm the update
    \Log::info('Player updated successfully:', $playersu21->toArray());

    // Redirect back to the index with a success message
    return redirect()->route('playersu21.index')->with('success', 'Player updated successfully.');
}


    // Remove the specified U21 player from the database
    public function destroy(PlayerU21 $playersu21) // Utilisation correcte du modèle
    {
        if ($playersu21->photo) {
            Storage::disk('public')->delete($playersu21->photo);
        }

        $playersu21->delete();

        return redirect()->route('playersu21.index')->with('success', 'U21 Player deleted successfully.');
    }
}

