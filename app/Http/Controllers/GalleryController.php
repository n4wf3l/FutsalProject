<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    // ————————— PUBLIC —————————

    public function publicIndex()
    {
        SeoMeta::share(
            'Galerie photos — Dina Kenitra FC',
            'Les meilleurs instants du club en images : matchs, coulisses, entraînements et événements de Dina Kenitra Futsal Club.'
        );

        return Inertia::render('Galleries', [
            'galleries' => Gallery::withCount('photos')->latest()->paginate(12),
        ]);
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);

        $description = $gallery->description
            ? SeoMeta::fromHtml($gallery->description)
            : 'Galerie photos Dina Kenitra Futsal Club.';

        SeoMeta::share(
            $gallery->name . ' — Galerie Dina Kenitra FC',
            $description,
            $gallery->cover_image ? asset('storage/' . $gallery->cover_image) : null,
            'article'
        );

        return Inertia::render('GalleryShow', [
            'gallery' => $gallery,
            'photos' => $gallery->photos,
        ]);
    }

    // ————————— ADMIN —————————

    public function index()
    {
        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => Gallery::withCount('photos')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Galleries/Form', ['gallery' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('gallery_covers', 'public');
        }
        Gallery::create($data);
        return redirect()->route('galleries.index')->with('success', 'Galerie créée.');
    }

    public function edit(Gallery $gallery)
    {
        return Inertia::render('Admin/Galleries/Form', ['gallery' => $gallery]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validated($request);
        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('gallery_covers', 'public');
        } else {
            unset($data['cover_image']);
        }
        $gallery->update($data);
        return redirect()->route('galleries.index')->with('success', 'Galerie mise à jour.');
    }

    public function destroy(Gallery $gallery)
    {
        foreach ($gallery->photos as $photo) {
            if ($photo->image) {
                Storage::disk('public')->delete($photo->image);
            }
        }
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }
        $gallery->delete();
        return redirect()->route('galleries.index')->with('success', 'Galerie supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:galleries,id',
        ]);
        $galleries = Gallery::whereIn('id', $data['ids'])->with('photos')->get();
        foreach ($galleries as $gallery) {
            foreach ($gallery->photos as $photo) {
                if ($photo->image) {
                    Storage::disk('public')->delete($photo->image);
                }
            }
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $gallery->delete();
        }
        return redirect()->route('galleries.index')->with('success', count($galleries) . ' galerie(s) supprimée(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);
    }
}
