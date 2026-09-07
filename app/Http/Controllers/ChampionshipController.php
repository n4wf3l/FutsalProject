<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionshipController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Championships/Index', [
            'championships' => Championship::orderBy('season', 'desc')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Championships/Form', [
            'championship' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Championship::create($data);
        return redirect()->route('championships.index')->with('success', 'Championnat ajouté.');
    }

    public function edit(Championship $championship)
    {
        return Inertia::render('Admin/Championships/Form', [
            'championship' => $championship,
        ]);
    }

    public function update(Request $request, Championship $championship)
    {
        $data = $this->validated($request);
        $championship->update($data);
        return redirect()->route('championships.index')->with('success', 'Championnat mis à jour.');
    }

    public function destroy(Championship $championship)
    {
        $championship->delete();
        return redirect()->route('championships.index')->with('success', 'Championnat supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'season' => 'required|string|max:255',
        ]);
    }
}
