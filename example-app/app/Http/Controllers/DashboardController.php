<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clubInfo = ClubInfo::first();
        $userSettings = UserSetting::where('user_id', $user->id)->first();
        $backgroundImages = BackgroundImage::all(); // Récupérer les images de fond

        return view('dashboard', compact('clubInfo', 'userSettings', 'backgroundImages'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $userSettings = UserSetting::firstOrNew(['user_id' => $user->id]);

        $userSettings->theme_color_primary = $request->input('theme_color_primary');
        $userSettings->theme_color_secondary = $request->input('theme_color_secondary');

        if ($request->hasFile('logo')) {
            if ($userSettings->logo) {
                \Storage::delete('public/' . $userSettings->logo);
            }
            $userSettings->logo = $request->file('logo')->store('logos', 'public');
        }

        $userSettings->save();

        return redirect()->back()->with('status', 'Settings updated successfully!');
    }

    public function storeBackgroundImage(Request $request)
{
    // Validation du fichier d'image
    $request->validate([
        'background_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'assigned_page' => 'nullable|string',
    ]);

    // Gestion du téléchargement de l'image
    $imagePath = $request->file('background_image')->store('background_images', 'public');

    // Si une page est assignée, désassigner toutes les autres images de cette page
    if ($request->assigned_page) {
        \App\Models\BackgroundImage::where('assigned_page', $request->assigned_page)
            ->update(['assigned_page' => null]);
    }

    // Créer une nouvelle entrée pour l'image avec la page assignée
    \App\Models\BackgroundImage::create([
        'image_path' => $imagePath,
        'assigned_page' => $request->assigned_page,
    ]);

    return redirect()->back()->with('success', 'Image de fond ajoutée et assignée avec succès.');
}

    public function deleteBackgroundImage($id)
    {
        $image = BackgroundImage::findOrFail($id);

        // Supprimer le fichier physique
        Storage::delete('public/' . $image->image_path);

        // Supprimer l'entrée de la base de données
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function assignBackground(Request $request)
    {
        $image = BackgroundImage::find($request->image_id);
    
        if ($image) {
            // Si une page est sélectionnée
            if ($request->page) {
                // Désassigner toutes les autres images de cette page
                BackgroundImage::where('assigned_page', $request->page)
                    ->update(['assigned_page' => null]);
    
                // Assigner l'image à la page sélectionnée
                $image->assigned_page = $request->page;
            } else {
                // Si "Aucune page" est sélectionnée, désassigner l'image
                $image->assigned_page = null;
            }
    
            $image->save();
        }
    
        return redirect()->back()->with('success', 'L\'image de fond a été mise à jour avec succès.');
    }
}
