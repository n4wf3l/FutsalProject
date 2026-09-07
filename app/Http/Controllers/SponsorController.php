<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SponsorController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sponsors/Index', [
            'sponsors' => Sponsor::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Sponsors/Form', ['sponsor' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['website'] = $this->normalizeWebsite($data['website'] ?? null);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create($data);
        return redirect()->route('sponsors.index')->with('success', 'Sponsor ajouté.');
    }

    public function edit(Sponsor $sponsor)
    {
        return Inertia::render('Admin/Sponsors/Form', ['sponsor' => $sponsor]);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $data = $this->validated($request);
        $data['website'] = $this->normalizeWebsite($data['website'] ?? null);

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $data['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($data);
        return redirect()->route('sponsors.index')->with('success', 'Sponsor mis à jour.');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo) {
            Storage::disk('public')->delete($sponsor->logo);
        }
        $sponsor->delete();
        return redirect()->route('sponsors.index')->with('success', 'Sponsor supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:sponsors,id',
        ]);

        $sponsors = Sponsor::whereIn('id', $data['ids'])->get();
        foreach ($sponsors as $sponsor) {
            if ($sponsor->logo) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $sponsor->delete();
        }

        return redirect()->route('sponsors.index')->with('success', count($sponsors) . ' sponsor(s) supprimé(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);
    }

    private function normalizeWebsite(?string $website): ?string
    {
        if (! $website) {
            return null;
        }
        $website = trim($website);
        if ($website === '') {
            return null;
        }
        if (! preg_match('/^https?:\/\//i', $website)) {
            $website = 'https://' . ltrim($website, '/');
        }
        return $website;
    }
}
