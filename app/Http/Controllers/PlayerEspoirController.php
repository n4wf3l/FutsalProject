<?php

namespace App\Http\Controllers;

use App\Models\PlayerEspoir;
use App\Support\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlayerEspoirController extends Controller
{
    public function publicIndex()
    {
        SeoMeta::share(
            'Espoirs — Dina Kenitra FC',
            'L\'effectif espoirs de Dina Kenitra Futsal Club. La relève qui forge aujourd\'hui l\'ADN du club.'
        );

        return Inertia::render('Espoirs', [
            'players' => PlayerEspoir::orderBy('number', 'asc')->get(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/Espoirs/Index', [
            'players' => PlayerEspoir::orderBy('number', 'asc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Espoirs/Form', [
            'player' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        PlayerEspoir::create($data);
        return redirect()->route('espoirs.index')->with('success', 'Joueur ajouté.');
    }

    public function edit(PlayerEspoir $espoir)
    {
        return Inertia::render('Admin/Espoirs/Form', [
            'player' => $espoir->makeVisible(['birthdate', 'height']),
        ]);
    }

    public function update(Request $request, PlayerEspoir $espoir)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($espoir->photo) {
                Storage::disk('public')->delete($espoir->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            unset($data['photo']);
        }
        $espoir->update($data);
        return redirect()->route('espoirs.index')->with('success', 'Joueur mis à jour.');
    }

    public function destroy(PlayerEspoir $espoir)
    {
        if ($espoir->photo) {
            Storage::disk('public')->delete($espoir->photo);
        }
        $espoir->delete();
        return redirect()->route('espoirs.index')->with('success', 'Joueur supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:players_espoirs,id',
        ]);

        $players = PlayerEspoir::whereIn('id', $data['ids'])->get();
        foreach ($players as $player) {
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }
            $player->delete();
        }

        return redirect()->route('espoirs.index')->with('success', count($players) . ' joueur(s) supprimé(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'birthdate' => 'required|date',
            'position' => 'required|string|max:255',
            'number' => 'required|integer',
            'nationality' => 'required|string|max:255',
            'height' => 'required|integer',
        ]);
    }
}
