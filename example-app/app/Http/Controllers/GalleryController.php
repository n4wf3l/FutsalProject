<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        // Récupérer les galeries avec pagination (10 galeries par page par exemple)
        $galleries = Gallery::paginate(10);
    
        // Retourner la vue pour afficher les galeries
        return view('galleries.index', compact('galleries'));
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        $photos = $gallery->photos;

        return view('galleries.show', compact('gallery', 'photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('gallery_covers', 'public');
        }

        Gallery::create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $coverImagePath,
        ]);

        return redirect()->route('galleries.index')->with('success', 'Gallery created successfully.');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }

            $gallery->cover_image = $request->file('cover_image')->store('gallery_covers', 'public');
        }

        $gallery->update([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $gallery->cover_image,
        ]);

        return redirect()->route('galleries.index')->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        // Supprimer l'image de couverture si elle existe
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        $gallery->delete();
        return redirect()->route('galleries.index')->with('success', 'Gallery deleted successfully.');
    }
}
