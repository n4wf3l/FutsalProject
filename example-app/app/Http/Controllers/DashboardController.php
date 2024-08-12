<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userSettings = UserSetting::firstOrNew(['user_id' => $user->id]);
        
        return view('dashboard', compact('userSettings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $userSettings = UserSetting::firstOrNew(['user_id' => $user->id]);

        $userSettings->theme_color_primary = $request->input('theme_color_primary');
        $userSettings->theme_color_secondary = $request->input('theme_color_secondary');

        if ($request->hasFile('logo')) {
            if ($userSettings->logo) {
                \Storage::delete('public/' . $userSettings->logo);
            }
            $userSettings->logo = $request->file('logo')->store('logos', 'public');
        }

        $userSettings->save();

        return redirect()->back()->with('status', 'Settings updated successfully!');
    }
}
