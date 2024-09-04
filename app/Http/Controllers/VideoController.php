<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BackgroundImage;

class VideoController extends Controller
{
    public function index()
{
    // Utiliser paginate(10) pour récupérer les vidéos avec pagination
    $videos = Video::latest()->paginate(10); 
    $backgroundImage = BackgroundImage::where('assigned_page', 'videos')->latest()->first();
    return view('videos.index', compact('videos', 'backgroundImage'));
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('videos', 'public');

        Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'image' => $imagePath,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video created successfully.');
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $video->image);
            $imagePath = $request->file('image')->store('videos', 'public');
            $video->image = $imagePath;
        }

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'image' => $video->image,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        Storage::delete('public/' . $video->image);
        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video deleted successfully.');
    }
}
