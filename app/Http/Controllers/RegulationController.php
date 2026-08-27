<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Regulation;
use App\Models\AboutSection;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
    public function index()
    {
        return Inertia::render('About', [
            'regulations' => Regulation::all(),
            'sections' => AboutSection::all(),
        ]);
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