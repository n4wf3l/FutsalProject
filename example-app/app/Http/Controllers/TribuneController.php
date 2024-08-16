<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tribune;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TribuneController extends Controller
{
    /**
     * Display a listing of the resource.-
     */
    public function index()
    {
        $tribunes = Tribune::all();
        return view('fanshop', compact('tribunes'));
    }
    
    /**
     * Affiche le formulaire pour créer une nouvelle tribune.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('tribunes.create');
    }

    /**
     * Enregistre une nouvelle tribune dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'photo' => 'nullable|image|max:2048',
            'available_seats' => 'required|integer|min:0', // Validation for available seats
        ]);

        if ($request->hasFile('photo')) {
            // Supprime l'ancienne photo si elle existe
            $oldPhoto = Tribune::first()->photo;
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Stocke la nouvelle photo
            $photoPath = $request->file('photo')->store('tribune_photos', 'public');

            // Met à jour toutes les tribunes avec la nouvelle photo
            Tribune::query()->update(['photo' => $photoPath]);
        }

        // Crée une nouvelle entrée pour la tribune
        Tribune::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'photo' => $photoPath ?? Tribune::first()->photo,
            'available_seats' => $request->available_seats, // Adding available seats
        ]);

        return redirect()->route('fanshop.index')->with('success', 'Tribune created successfully!');
    }

    /**
     * Affiche le formulaire pour éditer une tribune existante.
     *
     * @param  \App\Models\Tribune  $tribune
     * @return \Illuminate\View\View
     */
    public function edit(Tribune $tribune)
    {
        return view('tribunes.edit', compact('tribune'));
    }

    /**
     * Met à jour une tribune dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tribune  $tribune
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tribune $tribune)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'photo' => 'nullable|image|max:2048',
            'available_seats' => 'required|integer|min:0', // Validation for available seats
        ]);

        if ($request->hasFile('photo')) {
            // Supprime l'ancienne photo si elle existe
            $oldPhoto = Tribune::first()->photo;
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Stocke la nouvelle photo
            $photoPath = $request->file('photo')->store('tribune_photos', 'public');

            // Met à jour toutes les tribunes avec la nouvelle photo
            Tribune::query()->update(['photo' => $photoPath]);
        }

        // Met à jour uniquement cette tribune avec les nouveaux détails, sauf la photo (qui a déjà été mise à jour globalement)
        $tribune->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'available_seats' => $request->available_seats, // Updating available seats
        ]);

        return redirect()->route('fanshop.index')->with('success', 'Tribune updated successfully!');
    }

    /**
     * Supprime une tribune de la base de données.
     *
     * @param  \App\Models\Tribune  $tribune
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tribune $tribune)
    {
        // Récupérer le chemin de l'image de la tribune
        $imagePath = $tribune->photo;
    
        // Supprimer la tribune de la base de données
        $tribune->delete();
    
        // Vérifier si l'image est utilisée par d'autres tribunes
        $isImageUsedElsewhere = Tribune::where('photo', $imagePath)->exists();
    
        // Si l'image n'est plus utilisée, la supprimer du stockage
        if (!$isImageUsedElsewhere && $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
    
        return redirect()->route('fanshop.index')->with('success', 'Tribune deleted successfully!');
    }
}
