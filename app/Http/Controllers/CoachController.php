<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CoachController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Coaches/Index', [
            'coaches' => Coach::orderBy('last_name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Coaches/Form', ['coach' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        Coach::create($data);
        return redirect()->route('coaches.index')->with('success', 'Coach ajouté.');
    }

    public function edit(Coach $coach)
    {
        return Inertia::render('Admin/Coaches/Form', ['coach' => $coach]);
    }

    public function update(Request $request, Coach $coach)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($coach->photo) {
                Storage::disk('public')->delete($coach->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        $coach->update($data);
        return redirect()->route('coaches.index')->with('success', 'Coach mis à jour.');
    }

    public function destroy(Coach $coach)
    {
        if ($coach->photo) {
            Storage::disk('public')->delete($coach->photo);
        }
        $coach->delete();
        return redirect()->route('coaches.index')->with('success', 'Coach supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:coaches,id',
        ]);

        $coaches = Coach::whereIn('id', $data['ids'])->get();
        foreach ($coaches as $coach) {
            if ($coach->photo) {
                Storage::disk('public')->delete($coach->photo);
            }
            $coach->delete();
        }

        return redirect()->route('coaches.index')->with('success', count($coaches) . ' coach(s) supprimé(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'coaching_since' => 'required|date',
            'birth_city' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);
    }
}
