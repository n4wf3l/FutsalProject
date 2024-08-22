<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRegistrationStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (env('REGISTRATION_OPEN', false) == 'false') {
            return redirect('/')->with('error', 'Registrations are currently closed.');
        }

        return $next($request);
    }
}
