<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeasonController extends Controller
{
    // ————————— PUBLIC —————————

    public function publicIndex()
    {
        $seasons = Season::orderByDesc('season_start_year')->get();

        SeoMeta::share(
            'Historique du club — Dina Kenitra FC',
            'Le parcours saison par saison de Dina Kenitra Futsal Club depuis 2010 : divisions, classements, Coupe du Trône, coachs et faits marquants.'
        );

        return Inertia::render('Historique', [
            'seasons' => $seasons,
        ]);
    }

    // ————————— ADMIN —————————

    public function index()
    {
        return Inertia::render('Admin/Seasons/Index', [
            'seasons' => Season::orderByDesc('season_start_year')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Seasons/Form', ['season' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Season::create($data);
        return redirect()->route('seasons.index')->with('success', 'Saison ajoutée.');
    }

    public function edit(Season $season)
    {
        return Inertia::render('Admin/Seasons/Form', ['season' => $season]);
    }

    public function update(Request $request, Season $season)
    {
        $data = $this->validated($request, $season->id);
        $season->update($data);
        return redirect()->route('seasons.index')->with('success', 'Saison mise à jour.');
    }

    public function destroy(Season $season)
    {
        $season->delete();
        return redirect()->route('seasons.index')->with('success', 'Saison supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:seasons,id',
        ]);
        Season::whereIn('id', $data['ids'])->delete();
        return redirect()->route('seasons.index')->with('success', count($data['ids']) . ' saison(s) supprimée(s).');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'season_label' => 'required|string|max:32|unique:seasons,season_label' . ($ignoreId ? ',' . $ignoreId : ''),
            'season_start_year' => 'required|integer|min:1900|max:2100',
            'division' => 'required|string|max:64',
            'position' => 'nullable|integer|min:1|max:100',
            'position_label' => 'nullable|string|max:64',
            'cup_result' => 'nullable|string|max:64',
            'coach' => 'nullable|string|max:120',
            'badge' => 'nullable|string|max:32',
            'notes' => 'nullable|string|max:2000',
        ]);
    }
}
