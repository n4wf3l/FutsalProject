<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\BackgroundImage;
use App\Models\Article;

class PressReleaseController extends Controller
{
    public function index()
    {
        $pressReleases = PressRelease::orderBy('created_at', 'desc')->paginate(4);
        $backgroundImage = BackgroundImage::where('assigned_page', 'press_releases')->latest()->first();
        return view('press_releases.index', compact('pressReleases', 'backgroundImage'));
    }

    public function create()
    {
        return view('press_releases.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|max:2048', // Validation de l'image
    ]);

    $slug = $this->createUniqueSlug($request->title); // Générer un slug unique

    $imagePath = null;

    // Gestion du téléchargement de l'image
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('press_releases', 'public');
    }

    PressRelease::create([
        'title' => $request->title,
        'content' => $request->content,
        'image' => $imagePath, // Stocker le chemin de l'image
        'slug' => $slug,
    ]);

    return redirect()->route('press_releases.index')->with('success', 'Press release created successfully.');
}

    public function edit(PressRelease $pressRelease)
    {
        return view('press_releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, PressRelease $pressRelease)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Validation de l'image
        ]);
    
        // Générer un slug unique uniquement si le titre change
        if ($request->title !== $pressRelease->title) {
            $slug = $this->createUniqueSlug($request->title);
        } else {
            $slug = $pressRelease->slug;
        }
    
        // Gestion du téléchargement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($pressRelease->image) {
                Storage::disk('public')->delete($pressRelease->image);
            }
            $imagePath = $request->file('image')->store('press_releases', 'public');
        } else {
            $imagePath = $pressRelease->image; // Garder l'ancienne image si pas de nouvelle image
        }
    
        $pressRelease->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath, // Mettre à jour le chemin de l'image
            'slug' => $slug,
        ]);
    
        return redirect()->route('press_releases.index')->with('success', 'Press release updated successfully.');
    }

    public function show($slug)
    {
        $pressRelease = PressRelease::where('slug', $slug)->firstOrFail();
    
        // Récupérer les 10 articles les plus récents avec pagination
        $recentArticles = \App\Models\Article::orderBy('created_at', 'desc')->paginate(10);
    
        return view('press_releases.show', compact('pressRelease', 'recentArticles'));
    }

    public function destroy(PressRelease $pressRelease)
    {
        // Supprimer l'image associée si elle existe
        if ($pressRelease->image) {
            Storage::disk('public')->delete($pressRelease->image);
        }

        $pressRelease->delete();
        return redirect()->route('press_releases.index')->with('success', 'Press release deleted successfully.');
    }

    private function createUniqueSlug($title)
    {
        // Créer un slug de base
        $slug = Str::slug($title);

        // Vérifier si le slug existe déjà
        $count = PressRelease::where('slug', 'LIKE', "{$slug}%")->count();

        // S'il existe, ajouter un suffixe
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
