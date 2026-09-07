<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tribune;
use App\Models\Game;
use App\Models\ClubInfo;
use App\Models\Championship;
use Illuminate\Support\Facades\Storage;

class TribuneController extends Controller
{
    public function index()
    {
        $championship = Championship::first();
        $clubInfo = ClubInfo::first();
        $clubName = $clubInfo->club_name ?? 'Dina Kénitra FC';
        $clubPrefix = substr($clubName, 0, 4);

        $nextGame = Game::with(['homeTeam', 'awayTeam'])
            ->where('match_date', '>=', now()->startOfDay())
            ->whereHas('homeTeam', function ($query) use ($clubPrefix) {
                $query->where('name', 'LIKE', "$clubPrefix%");
            })
            ->orderBy('match_date', 'asc')
            ->first();

        return Inertia::render('Fanshop', [
            'tribunes' => Tribune::all(),
            'nextGame' => $nextGame,
            'championship' => $championship,
            'clubPrefix' => $clubPrefix,
        ]);
    }

    public function adminIndex()
    {
        return Inertia::render('Admin/Tribunes/Index', [
            'tribunes' => Tribune::orderBy('name', 'asc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Tribunes/Form', [
            'tribune' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'available_seats' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('tribune_photos', 'public');
        }

        Tribune::create($data);

        return redirect('/tribunes')->with('success', 'Tribune ajoutée.');
    }

    public function edit(Tribune $tribune)
    {
        return Inertia::render('Admin/Tribunes/Form', [
            'tribune' => $tribune,
        ]);
    }

    public function update(Request $request, Tribune $tribune)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'available_seats' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            if ($tribune->photo) {
                Storage::disk('public')->delete($tribune->photo);
            }
            $data['photo'] = $request->file('photo')->store('tribune_photos', 'public');
        }

        $tribune->update($data);

        return redirect('/tribunes')->with('success', 'Tribune mise à jour.');
    }

    public function destroy(Tribune $tribune)
    {
        if ($tribune->photo) {
            Storage::disk('public')->delete($tribune->photo);
        }

        $tribune->delete();

        return redirect('/tribunes')->with('success', 'Tribune supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:tribunes,id',
        ]);

        $tribunes = Tribune::whereIn('id', $data['ids'])->get();
        foreach ($tribunes as $tribune) {
            if ($tribune->photo) {
                Storage::disk('public')->delete($tribune->photo);
            }
            $tribune->delete();
        }

        return redirect('/tribunes')->with('success', count($tribunes) . ' tribune(s) supprimée(s).');
    }
}
