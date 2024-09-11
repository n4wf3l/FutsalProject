<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserSettingController extends Controller
{
    public function showDashboard()
    {
        $userSettings = UserSetting::where('user_id', Auth::id())->first();
        return view('dashboard', compact('userSettings'));
    }

  public function update(Request $request)
{
    $user = Auth::user();
    $userSettings = UserSetting::firstOrNew(['user_id' => $user->id]);

    $validatedData = $request->validate([
        'theme_color_primary' => 'nullable|string|max:7',
        'theme_color_secondary' => 'nullable|string|max:7',
        'club_name' => 'nullable|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->filled('theme_color_primary')) {
        $userSettings->theme_color_primary = $validatedData['theme_color_primary'];
    }

    if ($request->filled('theme_color_secondary')) {
        $userSettings->theme_color_secondary = $validatedData['theme_color_secondary'];
    }

    if ($request->filled('club_name')) {
        $userSettings->club_name = $validatedData['club_name'];
    }

    if ($request->hasFile('logo')) {
        if ($userSettings->logo) {
            Storage::delete('public/' . $userSettings->logo);
        }
        $userSettings->logo = $request->file('logo')->store('logos', 'public');
    }

    $userSettings->save();

    return redirect()->back()->with('status', 'Settings updated successfully!');
}

}
