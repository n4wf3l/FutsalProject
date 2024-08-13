<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;

class CoachController extends Controller
{
    public function index()
    {


    }

    public function create()
    {
        return view('coach.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birth_date' => 'required|date',
            'coaching_since' => 'required|date',
            'birth_city' => 'required|max:255',
            'nationality' => 'required|max:255',
            'description' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $data = $request->all();
    
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
    
        Coach::create($data);
    
        return redirect()->route('teams')->with('success', 'Coach created successfully.');
    }
    
    public function update(Request $request, Coach $coach)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birth_date' => 'required|date',
            'coaching_since' => 'required|date',
            'birth_city' => 'required|max:255',
            'nationality' => 'required|max:255',
            'description' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $data = $request->all();
    
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si une nouvelle est téléchargée
            if ($coach->photo) {
                Storage::disk('public')->delete($coach->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
    
        $coach->update($data);
    
        return redirect()->route('teams')->with('success', 'Coach updated successfully.');
    }

    public function edit($id)
    {
        // Récupérer le coach par son ID
        $coach = Coach::findOrFail($id);

        // Retourner la vue d'édition avec les données du coach
        return view('coach.edit', compact('coach'));
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();

        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully.');
    }
}
