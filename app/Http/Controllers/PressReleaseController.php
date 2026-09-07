<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PressReleaseController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PressReleases/Index', [
            'pressReleases' => PressRelease::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PressReleases/Form', [
            'pressRelease' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('press_releases', 'public');
        }

        PressRelease::create($data);

        return redirect()->route('press_releases.index')->with('success', 'Communiqué créé.');
    }

    public function show(PressRelease $pressRelease)
    {
        return redirect()->route('press_releases.index');
    }

    public function edit(PressRelease $pressRelease)
    {
        return Inertia::render('Admin/PressReleases/Form', [
            'pressRelease' => $pressRelease,
        ]);
    }

    public function update(Request $request, PressRelease $pressRelease)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        if ($data['title'] !== $pressRelease->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $pressRelease->id);
        }

        if ($request->hasFile('image')) {
            if ($pressRelease->image) {
                Storage::disk('public')->delete($pressRelease->image);
            }
            $data['image'] = $request->file('image')->store('press_releases', 'public');
        } else {
            unset($data['image']);
        }

        $pressRelease->update($data);

        return redirect()->route('press_releases.index')->with('success', 'Communiqué mis à jour.');
    }

    public function destroy(PressRelease $pressRelease)
    {
        if ($pressRelease->image) {
            Storage::disk('public')->delete($pressRelease->image);
        }

        $pressRelease->delete();

        return redirect()->route('press_releases.index')->with('success', 'Communiqué supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:press_releases,id',
        ]);

        $pressReleases = PressRelease::whereIn('id', $data['ids'])->get();
        foreach ($pressReleases as $pressRelease) {
            if ($pressRelease->image) {
                Storage::disk('public')->delete($pressRelease->image);
            }
            $pressRelease->delete();
        }

        return redirect()->route('press_releases.index')->with('success', count($pressReleases) . ' communiqué(s) supprimé(s).');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (PressRelease::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
