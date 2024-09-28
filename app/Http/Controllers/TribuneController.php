<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tribune;
use App\Models\Game;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Storage;
use App\Models\Championship;

class TribuneController extends Controller
{
    public function index()
    {
        $tribunes = Tribune::all();
        $championship = Championship::first();
        $backgroundImage = BackgroundImage::where('assigned_page', 'fanshop')->latest()->first();
        $clubInfo = ClubInfo::first();
        $clubName = $clubInfo->club_name ?? 'Dina Kénitra FC';
        $clubPrefix = substr($clubName, 0, 4);
    
        $nextGame = Game::where('match_date', '>=', now()->startOfDay())
            ->whereHas('homeTeam', function ($query) use ($clubPrefix) {
                $query->where('name', 'LIKE', "$clubPrefix%");
            })
            ->orderBy('match_date', 'asc')
            ->first();
    
        return view('fanshop', compact('tribunes', 'nextGame', 'backgroundImage', 'championship'));
    }

    public function create()
    {
        return view('tribunes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'photo' => 'nullable|image|max:2048',
            'available_seats' => 'required|integer|min:0',
        ]);

        // Gérer la photo
        if ($request->hasFile('photo')) {
            // Supprime l'ancienne photo si elle existe
            $existingPhoto = Tribune::first()->photo ?? null;
            if ($existingPhoto) {
                Storage::disk('public')->delete($existingPhoto);
            }

            // Stocke la nouvelle photo
            $photoPath = $request->file('photo')->store('tribune_photos', 'public');

            // Met à jour toutes les tribunes avec la nouvelle photo
            Tribune::query()->update(['photo' => $photoPath]);
        }

        Tribune::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'photo' => $photoPath ?? Tribune::first()->photo ?? null,
            'available_seats' => $request->available_seats,
        ]);

        return redirect()->route('fanshop.index')->with('success', 'Tribune created successfully!');
    }

    public function edit(Tribune $tribune)
    {
        return view('tribunes.edit', compact('tribune'));
    }

    public function update(Request $request, Tribune $tribune)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'photo' => 'nullable|image|max:2048',
            'available_seats' => 'required|integer|min:0',
        ]);

        // Gérer la photo
        if ($request->hasFile('photo')) {
            // Supprime l'ancienne photo si elle existe
            $existingPhoto = Tribune::first()->photo ?? null;
            if ($existingPhoto) {
                Storage::disk('public')->delete($existingPhoto);
            }

            // Stocke la nouvelle photo
            $photoPath = $request->file('photo')->store('tribune_photos', 'public');

            // Met à jour toutes les tribunes avec la nouvelle photo
            Tribune::query()->update(['photo' => $photoPath]);

            // Mettre à jour la photo de la tribune en cours
            $tribune->photo = $photoPath;
        }

        $tribune->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'available_seats' => $request->available_seats,
        ]);

        return redirect()->route('fanshop.index')->with('success', 'Tribune updated successfully!');
    }

    public function destroy(Tribune $tribune)
    {
        $imagePath = $tribune->photo;
    
        $tribune->delete();
    
        if ($imagePath && !Tribune::where('photo', $imagePath)->exists()) {
            Storage::disk('public')->delete($imagePath);
        }
    
        return redirect()->route('fanshop.index')->with('success', 'Tribune deleted successfully!');
    }
}
