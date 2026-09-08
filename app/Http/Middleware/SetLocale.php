<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED = ['fr', 'en', 'ar'];
    public const DEFAULT = 'fr';

    public function handle(Request $request, Closure $next): Response
    {
        $query = $request->query('lang');
        $cookie = $request->cookie('locale');

        if ($query && in_array($query, self::SUPPORTED, true)) {
            App::setLocale($query);
            cookie()->queue('locale', $query, 60 * 24 * 365);
        } elseif ($cookie && in_array($cookie, self::SUPPORTED, true)) {
            App::setLocale($cookie);
        } else {
            $header = $request->getPreferredLanguage(self::SUPPORTED);
            App::setLocale($header ?: self::DEFAULT);
        }

        $response = $next($request);

        // Advertise the language of the served response to crawlers, caches,
        // proxies and assistive tools.
        $response->headers->set('Content-Language', App::getLocale());

        return $response;
    }
}
