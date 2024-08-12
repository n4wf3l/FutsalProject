<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;

class UserSettingController extends Controller
{
    public function update(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'theme_color_primary' => 'required|string',
            'theme_color_secondary' => 'required|string',
            'club_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Assurez-vous que vous avez une seule entrée globale
        $siteSettings = SiteSetting::firstOrCreate([]);

        // Mise à jour des valeurs
        $siteSettings->theme_color_primary = $validatedData['theme_color_primary'];
        $siteSettings->theme_color_secondary = $validatedData['theme_color_secondary'];
        $siteSettings->club_name = $validatedData['club_name'];

        // Gestion du logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $siteSettings->logo = $logoPath;
        }

        // Sauvegarde des paramètres
        $siteSettings->save();

        // Redirection vers le tableau de bord avec un message de succès
        return redirect()->route('dashboard')->with('success', 'Settings updated successfully.');
    }
}

