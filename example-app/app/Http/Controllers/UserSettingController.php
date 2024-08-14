<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    public function showDashboard()
    {
        $userSettings = UserSetting::where('user_id', Auth::id())->first();
        return view('dashboard', compact('userSettings'));
    }

    public function update(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'theme_color_primary' => 'required|string|max:7',
            'theme_color_secondary' => 'required|string|max:7',
            'club_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Récupérer ou créer les paramètres de l'utilisateur
        $siteSettings = UserSetting::where('user_id', Auth::id())->first();

        if (!$siteSettings) {
            $siteSettings = new UserSetting();
            $siteSettings->user_id = Auth::id(); // Associer l'utilisateur à ce paramètre
        }

        // Mise à jour des couleurs
        $siteSettings->theme_color_primary = $validatedData['theme_color_primary'];
        $siteSettings->theme_color_secondary = $validatedData['theme_color_secondary'];
        $siteSettings->club_name = $validatedData['club_name'];

        // Mise à jour du logo si un fichier est téléchargé
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $siteSettings->logo = $logoPath;
        }

        $siteSettings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
