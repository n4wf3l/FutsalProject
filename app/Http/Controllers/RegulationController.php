<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulation;
use App\Models\AboutSection;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
    public function index()
    {
        $regulations = Regulation::all();

        // Récupérer les données pour la section About
        $sections = AboutSection::all();
        $clubInfo = ClubInfo::first(); 
        $clubName = 'FTA Clubinfo'; 
        $backgroundImage = BackgroundImage::where('assigned_page', 'about')->latest()->first();
        
        // Passez toutes les variables à la vue
        return view('about.index', compact('regulations', 'sections', 'clubInfo', 'clubName', 'backgroundImage'));
    }

    public function create()
    {
        return view('regulations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Stockage du fichier PDF
        $pdfPath = $request->file('pdf')->store('regulations', 'public');

        // Enregistrement en base de données
        Regulation::create([
            'title' => $request->title,
            'pdf_path' => $pdfPath,
        ]);

        return redirect()->route('about.index')->with('success', 'Règlementation ajoutée avec succès.');
    }

    public function destroy(Regulation $regulation)
{
    // Supprimer le fichier PDF du stockage
    Storage::disk('public')->delete($regulation->pdf_path);

    // Supprimer l'enregistrement de la base de données
    $regulation->delete();

    return redirect()->route('about.index')->with('success', 'PDF supprimé avec succès.');
}
}