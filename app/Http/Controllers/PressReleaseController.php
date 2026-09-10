<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PressReleaseController extends Controller
{
    // ————————— PUBLIC —————————

    public function publicIndex(Request $request)
    {
        $search = $request->string('search')->toString();

        $query = PressRelease::latest();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        SeoMeta::share(
            'Communiqués officiels — Dina Kenitra FC',
            'Les communiqués officiels de Dina Kenitra Futsal Club : décisions, annonces et prises de position du club.'
        );

        return Inertia::render('PressReleases/Index', [
            'pressReleases' => $query->paginate(9)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function publicShow($slug)
    {
        $pressRelease = PressRelease::where('slug', $slug)->firstOrFail();

        $recent = PressRelease::where('id', '!=', $pressRelease->id)
            ->latest()
            ->take(5)
            ->get();

        SeoMeta::share(
            $pressRelease->title . ' — Communiqué Dina Kenitra FC',
            SeoMeta::fromHtml($pressRelease->content ?? '') ?: 'Communiqué officiel Dina Kenitra Futsal Club.',
            $pressRelease->image ? asset('storage/' . $pressRelease->image) : null,
            'article'
        );

        return Inertia::render('PressReleases/Show', [
            'pressRelease' => $pressRelease,
            'recent' => $recent,
        ]);
    }

    // ————————— ADMIN —————————

    public function index()
    {
        return Inertia::render('Admin/PressReleases/Index', [
            'pressReleases' => PressRelease::latest()->get(),
        ]);
    }

    public function create()
    {
        // Pre-fill the image with the previous release's cover so the admin
        // does not have to re-upload the club crest every time.
        $lastImage = PressRelease::whereNotNull('image')
            ->latest()
            ->value('image');

        return Inertia::render('Admin/PressReleases/Form', [
            'pressRelease' => null,
            'defaultImage' => $lastImage,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'reuse_default_image' => 'nullable|boolean',
        ]);

        $reuseDefault = (bool) ($data['reuse_default_image'] ?? false);
        unset($data['reuse_default_image']);

        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('press_releases', 'public');
        } elseif ($reuseDefault) {
            $sourcePath = PressRelease::whereNotNull('image')
                ->latest()
                ->value('image');
            if ($sourcePath && Storage::disk('public')->exists($sourcePath)) {
                $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
                $copyPath = 'press_releases/' . Str::random(40) . '.' . $extension;
                Storage::disk('public')->copy($sourcePath, $copyPath);
                $data['image'] = $copyPath;
            }
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
