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
            'organizationLogo' => $clubInfo->organization_logo ? asset('storage/' . $clubInfo->organization_logo) : null,
            'facebook' => $clubInfo->facebook ?? '#',
            'city' => $clubInfo->city ?? 'Default City',
            'instagram' => $clubInfo->instagram ?? '#',
        ]);
    }

    public function store(Request $request)
    {
        // Validation des champs, en les rendant tous nullable si nécessaire
        $request->validate([
            'sportcomplex_location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'federation_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'president' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city' => 'nullable|string|max:255',
        ]);
    
        // Chercher la première entrée ou créer une nouvelle instance sans l'enregistrer
        $clubInfo = ClubInfo::firstOrNew([]);
    
        // Assigner les champs avec des valeurs par défaut si aucun champ n'est fourni
        $clubInfo->sportcomplex_location = $request->input('sportcomplex_location') ?? 'Default Location';
        $clubInfo->phone = $request->input('phone') ?? 'No phone available'; // Assurez-vous que 'phone' n'est pas null
        $clubInfo->email = $request->input('email') ?? 'No email available';
        $clubInfo->facebook = $request->input('facebook') ?? '#';
        $clubInfo->instagram = $request->input('instagram') ?? '#';
        $clubInfo->president = $request->input('president') ?? 'Unknown President';
        $clubInfo->latitude = $request->input('latitude') ?? 0.0;
        $clubInfo->longitude = $request->input('longitude') ?? 0.0;
        $clubInfo->city = $request->input('city') ?? 'Unknown City';
    
        // Gérer le téléchargement du logo de la fédération, s'il y en a un
        if ($request->hasFile('federation_logo')) {
            if ($clubInfo->federation_logo) {
                Storage::delete('public/' . $clubInfo->federation_logo);
            }
            $clubInfo->federation_logo = $request->file('federation_logo')->store('logos', 'public');
        }
    
        // Gérer le téléchargement du logo de l'organisation, s'il y en a un
        if ($request->hasFile('organization_logo')) {
            if ($clubInfo->organization_logo) {
                Storage::delete('public/' . $clubInfo->organization_logo);
            }
            $clubInfo->organization_logo = $request->file('organization_logo')->store('logos', 'public');
        }
    
        // Enregistrer les modifications dans la base de données
        $clubInfo->save();
    
        return redirect()->back()->with('success', 'Club information updated successfully.');
    }

    public function show($id)
    {
        $clubInfo = ClubInfo::find($id);
        return view('/', compact('clubInfo'));
    }
}
