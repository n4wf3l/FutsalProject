<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Staff/Index', [
            'staff' => Staff::orderBy('last_name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Staff/Form', ['staff' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        Staff::create($data);
        return redirect()->route('staff.index')->with('success', 'Membre ajouté.');
    }

    public function edit(Staff $staff)
    {
        return Inertia::render('Admin/Staff/Form', ['staff' => $staff]);
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        $staff->update($data);
        return redirect()->route('staff.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->photo) {
            Storage::disk('public')->delete($staff->photo);
        }
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Membre supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:staff,id',
        ]);

        $members = Staff::whereIn('id', $data['ids'])->get();
        foreach ($members as $member) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->delete();
        }

        return redirect()->route('staff.index')->with('success', count($members) . ' membre(s) supprimé(s).');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);
    }
}
