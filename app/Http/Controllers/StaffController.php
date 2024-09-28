<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff; 
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'position' => 'required|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider l'image
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public'); // Stocker l'image
        }

        Staff::create($request->all());

        return redirect()->route('teams')->with('success', 'Staff member created successfully.');
    }
    
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'position' => 'required|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider l'image
        ]);
    
        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne image si elle existe
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }

            $data['photo'] = $request->file('photo')->store('photos', 'public'); // Stocker la nouvelle image
        }
    
        $staff->update($data);

        // Rediriger vers la page 'teams' après la mise à jour d'un membre du personnel
        return redirect()->route('teams')->with('success', 'Staff member updated successfully.');
    }

    public function edit(Staff $staff)
    {
        // Retourner la vue d'édition avec les données du membre du staff
        return view('staff.edit', compact('staff'));
    }

    public function destroy(Staff $staff)
{
    $staff->delete();

    // Redirigez vers la page souhaitée, par exemple vers la page 'teams'
    return redirect()->route('teams')->with('success', 'Staff member deleted successfully.');
}
}
