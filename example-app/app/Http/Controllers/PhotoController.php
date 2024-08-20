<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index($galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        $photos = Photo::where('gallery_id', $galleryId)->get();

        return view('photos.index', compact('gallery', 'photos'));
    }

    public function create($galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);

        return view('photos.create', compact('gallery'));
    }

    public function store(Request $request, $galleryId)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        $gallery = Gallery::findOrFail($galleryId);

        $imagePath = $request->file('image')->store('photos', 'public');

        Photo::create([
            'gallery_id' => $gallery->id,
            'image' => $imagePath,
            'caption' => $request->caption,
        ]);

        return redirect()->route('galleries.show', $gallery->id)->with('success', 'Photo added successfully.');
    }

    public function edit($galleryId, $photoId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        $photo = Photo::findOrFail($photoId);

        return view('photos.edit', compact('gallery', 'photo'));
    }

    public function update(Request $request, $galleryId, $photoId)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        $photo = Photo::findOrFail($photoId);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($photo->image);
            $imagePath = $request->file('image')->store('photos', 'public');
            $photo->image = $imagePath;
        }

        $photo->caption = $request->caption;
        $photo->save();

        return redirect()->route('galleries.show', $galleryId)->with('success', 'Photo updated successfully.');
    }

    public function destroy($galleryId, $photoId)
    {
        $photo = Photo::findOrFail($photoId);

        Storage::disk('public')->delete($photo->image);
        $photo->delete();

        return redirect()->route('galleries.show', $galleryId)->with('success', 'Photo deleted successfully.');
    }

    public function storeMultiple(Request $request, $galleryId)
{
    $request->validate([
        'photos.*' => 'required|image|max:2048',
        'captions' => 'nullable|string|max:255',
    ]);

    $gallery = Gallery::findOrFail($galleryId);

    // Récupérer la légende fournie par l'utilisateur (le nom du photographe)
    $caption = $request->input('captions', '');

    foreach ($request->file('photos') as $file) {
        $path = $file->store('photos', 'public');

        Photo::create([
            'gallery_id' => $gallery->id,
            'image' => $path,
            'caption' => $caption,  // Appliquer la même légende à toutes les photos
        ]);
    }

    return redirect()->route('galleries.show', $galleryId)->with('success', 'Photos added successfully.');
}
    
}