<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index()
    {
        $sections = AboutSection::all();
        return view('about.index', compact('sections'));
    }

    public function create()
    {
        return view('about.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required', // Cela validera le champ dans le backend
    ]);

    AboutSection::create([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    return redirect()->route('about.index')->with('success', 'Section created successfully.');
}

public function edit(AboutSection $aboutSection)
{
    return view('about.edit', compact('aboutSection'));
}


    public function update(Request $request, AboutSection $aboutSection)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $aboutSection->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('about.index')->with('success', 'Section updated successfully.');
    }
    

    public function destroy(AboutSection $aboutSection)
    {
        $aboutSection->delete();

        return redirect()->route('about.index')->with('success', 'Section deleted successfully.');
    }
}