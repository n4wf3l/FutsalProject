<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PhotoController extends Controller
{
    public function index($galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        return Inertia::render('Admin/Galleries/Photos', [
            'gallery' => $gallery,
            'photos' => Photo::where('gallery_id', $galleryId)->orderBy('id', 'desc')->get(),
        ]);
    }

    public function create($galleryId)
    {
        return redirect()->route('galleries.photos.index', $galleryId);
    }

    public function store(Request $request, $galleryId)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'caption' => 'nullable|string|max:255',
        ]);

        $gallery = Gallery::findOrFail($galleryId);
        $imagePath = $request->file('image')->store('photos', 'public');

        Photo::create([
            'gallery_id' => $gallery->id,
            'image' => $imagePath,
            'caption' => $data['caption'] ?? null,
        ]);

        return redirect()->route('galleries.photos.index', $gallery->id)->with('success', 'Photo ajoutée.');
    }

    public function edit($galleryId, $photoId)
    {
        return redirect()->route('galleries.photos.index', $galleryId);
    }

    public function update(Request $request, $galleryId, $photoId)
    {
        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'caption' => 'nullable|string|max:255',
        ]);

        $photo = Photo::findOrFail($photoId);

        if ($request->hasFile('image')) {
            if ($photo->image) {
                Storage::disk('public')->delete($photo->image);
            }
            $photo->image = $request->file('image')->store('photos', 'public');
        }

        $photo->caption = $data['caption'] ?? null;
        $photo->save();

        return redirect()->route('galleries.photos.index', $galleryId)->with('success', 'Photo mise à jour.');
    }

    public function destroy($galleryId, $photoId)
    {
        $photo = Photo::findOrFail($photoId);
        if ($photo->image) {
            Storage::disk('public')->delete($photo->image);
        }
        $photo->delete();
        return redirect()->route('galleries.photos.index', $galleryId)->with('success', 'Photo supprimée.');
    }

    public function storeMultiple(Request $request, $galleryId)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'captions' => 'nullable|string|max:255',
        ]);

        $gallery = Gallery::findOrFail($galleryId);
        $caption = $request->input('captions', '') ?: null;

        foreach ($request->file('photos') as $file) {
            $path = $file->store('photos', 'public');
            Photo::create([
                'gallery_id' => $gallery->id,
                'image' => $path,
                'caption' => $caption,
            ]);
        }

        return redirect()->route('galleries.photos.index', $galleryId)->with('success', 'Photos ajoutées.');
    }
}
