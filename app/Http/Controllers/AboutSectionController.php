<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use Illuminate\Http\Request;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::all();
        $clubInfo = ClubInfo::first(); 
        $clubName = 'FTA Clubinfo'; 
    
        $backgroundImage = BackgroundImage::where('assigned_page', 'about')->latest()->first();
    
        // Remplacer les entités HTML dans les titres
        foreach ($sections as $section) {
            $section->title = str_replace('&#039;', "'", $section->title);
        }
    
        return view('about.index', compact('sections', 'clubInfo', 'clubName', 'backgroundImage'));
    }

    
    public function create()
    {
        return view('about.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
    ]);

    $title = html_entity_decode($validatedData['title'], ENT_QUOTES, 'UTF-8');

    AboutSection::create([
        'title' => $title,
        'content' => $validatedData['content'],
    ]);

    return redirect()->route('about.index')->with('success', 'Section created successfully.');
}

    public function edit(AboutSection $aboutSection)
    {
        // Decode HTML entities for content before editing
        $aboutSection->content = html_entity_decode($aboutSection->content);
        return view('about.edit', compact('aboutSection'));
    }

    public function update(Request $request, AboutSection $aboutSection)
{
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
    ]);

    $title = html_entity_decode($request->input('title'), ENT_QUOTES, 'UTF-8');

    $aboutSection->update([
        'title' => $title,
        'content' => $request->input('content'),
    ]);

    return redirect()->route('about.index')->with('success', 'Section updated successfully.');
}

    public function destroy(AboutSection $aboutSection)
    {
        $aboutSection->delete();

        return redirect()->route('about.index')->with('success', 'Section deleted successfully.');
    }
}