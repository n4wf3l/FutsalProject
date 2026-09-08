<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoController extends Controller
{
    // Public gallery of videos
    public function publicIndex()
    {
        SeoMeta::share(
            'Vidéos — Dina Kenitra FC',
            'Résumés de match, moments forts et coulisses du club de futsal Dina Kenitra en vidéo.'
        );

        return Inertia::render('Videos', [
            'videos' => Video::latest()->paginate(12),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/Videos/Index', [
            'videos' => Video::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Videos/Form', [
            'video' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $data['image'] = $request->file('image')->store('videos', 'public');

        Video::create($data);

        return redirect()->route('videos.index')->with('success', 'Vidéo créée.');
    }

    public function show(Video $video)
    {
        return redirect()->route('videos.index');
    }

    public function edit(Video $video)
    {
        return Inertia::render('Admin/Videos/Form', [
            'video' => $video,
        ]);
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($video->image) {
                Storage::disk('public')->delete($video->image);
            }
            $data['image'] = $request->file('image')->store('videos', 'public');
        } else {
            unset($data['image']);
        }

        $video->update($data);

        return redirect()->route('videos.index')->with('success', 'Vidéo mise à jour.');
    }

    public function destroy(Video $video)
    {
        if ($video->image) {
            Storage::disk('public')->delete($video->image);
        }

        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Vidéo supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:videos,id',
        ]);

        $videos = Video::whereIn('id', $data['ids'])->get();
        foreach ($videos as $video) {
            if ($video->image) {
                Storage::disk('public')->delete($video->image);
            }
            $video->delete();
        }

        return redirect()->route('videos.index')->with('success', count($videos) . ' vidéo(s) supprimée(s).');
    }
}
