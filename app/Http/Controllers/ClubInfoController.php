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
        
        return view('dashboard', [
            'clubInfo' => $clubInfo,
            'clubLocation' => $clubInfo->sportcomplex_location ?? 'Default Location',
            'phone' => $clubInfo->phone ?? '+32400 000 000', // Valeur par défaut pour le téléphone
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
    // Validation des champs
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
    
    // Chercher la première entrée ou créer une nouvelle instance
    $clubInfo = ClubInfo::firstOrNew([]);

    // Assigner les champs avec des valeurs par défaut si aucun champ n'est fourni
    $clubInfo->sportcomplex_location = $request->input('sportcomplex_location', $clubInfo->sportcomplex_location ?? 'Default Location');
    $clubInfo->phone = $request->input('phone', $clubInfo->phone ?? '+32400 000 000');
    $clubInfo->email = $request->input('email', $clubInfo->email ?? 'no.email@available.be');
    $clubInfo->facebook = $request->input('facebook', $clubInfo->facebook ?? '#');
    $clubInfo->instagram = $request->input('instagram', $clubInfo->instagram ?? '#');
    $clubInfo->president = $request->input('president', $clubInfo->president ?? 'Unknown President');
    $clubInfo->latitude = $request->input('latitude', $clubInfo->latitude ?? 0.0);
    $clubInfo->longitude = $request->input('longitude', $clubInfo->longitude ?? 0.0);
    $clubInfo->city = $request->input('city', $clubInfo->city ?? 'Unknown City');

    // Gérer le téléchargement des logos si présents
    if ($request->hasFile('federation_logo')) {
        if ($clubInfo->federation_logo) {
            Storage::delete('public/' . $clubInfo->federation_logo);
        }
        $clubInfo->federation_logo = $request->file('federation_logo')->store('logos', 'public');
    }

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

    public function destroyField($field)
    {
        $clubInfo = ClubInfo::first();
    
        // Liste des champs autorisés à être supprimés
        $deletableFields = ['sportcomplex_location', 'city', 'phone', 'email', 'facebook', 'instagram', 'president', 'latitude', 'longitude', 'organization_logo', 'federation_logo'];
    
        if ($clubInfo && in_array($field, $deletableFields)) {
            // Vérifiez si le champ à supprimer est un champ de fichier
            if ($field === 'organization_logo' || $field === 'federation_logo') {
                // Supprimez le fichier du stockage si un fichier est présent
                if ($clubInfo->$field) {
                    Storage::delete('public/' . $clubInfo->$field);
                }
            }
    
            // Met à jour le champ en utilisant une valeur par défaut
            switch ($field) {
                case 'sportcomplex_location':
                    $clubInfo->sportcomplex_location = 'Default Location'; // Valeur par défaut
                    break;
                case 'city':
                    $clubInfo->city = 'Default City'; // Valeur par défaut
                    break;
                case 'phone':
                    $clubInfo->phone = '+32400 000 000'; // Valeur par défaut
                    break;
                case 'email':
                    $clubInfo->email = 'no.email@available.be'; // Valeur par défaut
                    break;
                case 'facebook':
                    $clubInfo->facebook = '#'; // Valeur par défaut
                    break;
                case 'instagram':
                    $clubInfo->instagram = '#'; // Valeur par défaut
                    break;
                case 'president':
                    $clubInfo->president = 'Unknown President'; // Valeur par défaut
                    break;
                case 'latitude':
                    $clubInfo->latitude = 0.0; // Valeur par défaut
                    break;
                case 'longitude':
                    $clubInfo->longitude = 0.0; // Valeur par défaut
                    break;
            }
    
            // Enregistre les modifications
            $clubInfo->save();
    
            return redirect()->back()->with('success', 'Field deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Failed to delete the field.');
    }
}
