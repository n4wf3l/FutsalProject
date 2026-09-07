<?php

namespace App\Http\Controllers;

use App\Models\ClubInfo;
use App\Models\FlashMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ClubInfoController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'clubInfo' => ClubInfo::first(),
            'flashMessage' => FlashMessage::latest()->first(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sportcomplex_location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'president' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'federation_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $clubInfo = ClubInfo::firstOrNew([]);

        foreach (['sportcomplex_location', 'city', 'phone', 'email', 'president', 'facebook', 'instagram', 'latitude', 'longitude'] as $field) {
            if (array_key_exists($field, $data)) {
                $clubInfo->$field = $data[$field] ?? $clubInfo->$field;
            }
        }

        if ($request->hasFile('federation_logo')) {
            if ($clubInfo->federation_logo) {
                Storage::disk('public')->delete($clubInfo->federation_logo);
            }
            $clubInfo->federation_logo = $request->file('federation_logo')->store('logos', 'public');
        }

        if ($request->hasFile('organization_logo')) {
            if ($clubInfo->organization_logo) {
                Storage::disk('public')->delete($clubInfo->organization_logo);
            }
            $clubInfo->organization_logo = $request->file('organization_logo')->store('logos', 'public');
        }

        $clubInfo->save();

        return redirect()->back()->with('success', 'Réglages du club mis à jour.');
    }

    public function destroyField($field)
    {
        $clubInfo = ClubInfo::first();
        $deletableFields = ['sportcomplex_location', 'city', 'phone', 'email', 'facebook', 'instagram', 'president', 'latitude', 'longitude', 'organization_logo', 'federation_logo'];

        if (! $clubInfo || ! in_array($field, $deletableFields, true)) {
            return redirect()->back()->with('error', 'Champ invalide.');
        }

        if (in_array($field, ['organization_logo', 'federation_logo'], true) && $clubInfo->$field) {
            Storage::disk('public')->delete($clubInfo->$field);
            $clubInfo->$field = null;
        } else {
            $clubInfo->$field = null;
        }

        $clubInfo->save();
        return redirect()->back()->with('success', 'Champ vidé.');
    }
}
