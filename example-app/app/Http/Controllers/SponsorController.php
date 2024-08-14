<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    // Display a listing of sponsors (index)
    public function index()
    {
        $sponsors = Sponsor::all();
        return view('sponsors', compact('sponsors')); // Assuming you have sponsors/index.blade.php
    }

    // Show the form for creating a new sponsor (create)
    public function create()
    {
        return view('sponsors.create'); // Assuming you have sponsors/create.blade.php
    }

    // Store a newly created sponsor in storage (store)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create($validatedData);

        return redirect()->route('sponsors.index')->with('success', 'Sponsor created successfully.');
    }

    // Show the form for editing the specified sponsor (edit)
    public function edit(Sponsor $sponsor)
    {
        return view('sponsors.edit', compact('sponsor')); // Assuming you have sponsors/edit.blade.php
    }

    // Update the specified sponsor in storage (update)
    public function update(Request $request, Sponsor $sponsor)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $validatedData['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($validatedData);

        return redirect()->route('sponsors.index')->with('success', 'Sponsor updated successfully.');
    }

    // Remove the specified sponsor from storage (destroy)
    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo) {
            Storage::disk('public')->delete($sponsor->logo);
        }

        $sponsor->delete();

        return redirect()->route('sponsors.index')->with('success', 'Sponsor deleted successfully.');
    }
}
