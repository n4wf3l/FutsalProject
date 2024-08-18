<?php

namespace App\Http\Controllers;

use App\Models\ClubInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClubInfoController extends Controller
{
    public function index()
    {
        $clubInfo = ClubInfo::first(); // suppose qu'il y a une seule entrée pour le club
        
        // Passez ces informations à la vue
        return view('dashboard', [
            'clubInfo' => $clubInfo,
            'clubLocation' => $clubInfo->sportcomplex_location ?? 'Default Location',
            'phone' => $clubInfo->phone ?? 'No phone available',
            'email' => $clubInfo->email ?? 'No email available',
            'federationLogo' => $clubInfo->federation_logo ? asset('storage/' . $clubInfo->federation_logo) : null,
            'facebook' => $clubInfo->facebook ?? '#',
            'instagram' => $clubInfo->instagram ?? '#',
            'city' => $clubInfo->city ?? 'No city available', // Ajout de la ville
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'sportcomplex_location' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'federation_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'president' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'required|string|max:255', // Validation pour la ville
        ]);

        // Cherchez la première entrée ou créez-en une nouvelle sans enregistrer
        $clubInfo = ClubInfo::firstOrNew([]);

        // Mettez à jour les champs avec les données de la requête
        $clubInfo->fill($request->only([
            'sportcomplex_location',
            'phone',
            'email',
            'facebook',
            'instagram',
            'president',
            'latitude',
            'longitude',
            'city', // Ajout de la ville
        ]));

        // Gérer le téléchargement du logo de la fédération, s'il y en a un
        if ($request->hasFile('federation_logo')) {
            if ($clubInfo->federation_logo) {
                Storage::delete('public/' . $clubInfo->federation_logo);
            }
            $clubInfo->federation_logo = $request->file('federation_logo')->store('logos', 'public');
        }

        // Enregistrez les modifications
        $clubInfo->save();

        return redirect()->back()->with('success', 'Club information updated successfully.');
    }

    public function show($id)
    {
        $clubInfo = ClubInfo::find($id);
        return view('votre_vue', compact('clubInfo'));
    }
}