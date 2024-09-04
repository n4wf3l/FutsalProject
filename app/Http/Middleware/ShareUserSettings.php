<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;

class ShareUserSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userSettings = UserSetting::where('user_id', Auth::id())->first();

            if ($userSettings) {
                view()->share('userSettings', $userSettings);
            } else {
                // Vous pouvez ajouter un fallback ici si vous le souhaitez
                view()->share('userSettings', null);
            }
        }

        return $next($request);
    }
}

