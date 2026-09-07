<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutSectionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AboutSections/Index', [
            'sections' => AboutSection::orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/AboutSections/Form', [
            'section' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        AboutSection::create($data);
        return redirect()->route('about.index')->with('success', 'Section ajoutée.');
    }

    public function edit(AboutSection $aboutSection)
    {
        return Inertia::render('Admin/AboutSections/Form', [
            'section' => $aboutSection,
        ]);
    }

    public function update(Request $request, AboutSection $aboutSection)
    {
        $data = $this->validated($request);
        $aboutSection->update($data);
        return redirect()->route('about.index')->with('success', 'Section mise à jour.');
    }

    public function destroy(AboutSection $aboutSection)
    {
        $aboutSection->delete();
        return redirect()->route('about.index')->with('success', 'Section supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:about_sections,id',
        ]);

        $count = AboutSection::whereIn('id', $data['ids'])->delete();

        return redirect()->route('about.index')->with('success', $count . ' section(s) supprimée(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    }
}
