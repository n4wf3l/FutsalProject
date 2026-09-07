<?php

namespace App\Http\Controllers;

use App\Models\PlayerFeminine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PlayerFeminineController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Feminines/Index', [
            'players' => PlayerFeminine::orderBy('number', 'asc')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Feminines/Form', [
            'player' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        PlayerFeminine::create($data);
        return redirect()->route('feminines.index')->with('success', 'Joueuse ajoutée.');
    }

    public function edit(PlayerFeminine $feminine)
    {
        return Inertia::render('Admin/Feminines/Form', [
            'player' => $feminine,
        ]);
    }

    public function update(Request $request, PlayerFeminine $feminine)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($feminine->photo) {
                Storage::disk('public')->delete($feminine->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            unset($data['photo']);
        }
        $feminine->update($data);
        return redirect()->route('feminines.index')->with('success', 'Joueuse mise à jour.');
    }

    public function destroy(PlayerFeminine $feminine)
    {
        if ($feminine->photo) {
            Storage::disk('public')->delete($feminine->photo);
        }
        $feminine->delete();
        return redirect()->route('feminines.index')->with('success', 'Joueuse supprimée.');
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
