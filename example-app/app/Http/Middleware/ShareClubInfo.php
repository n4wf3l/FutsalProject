<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ClubInfo;
use Illuminate\Support\Facades\View;

class ShareClubInfo
{
    public function handle(Request $request, Closure $next)
    {
        $clubInfo = ClubInfo::first();
        $city = $clubInfo->city ?? 'Default City';
        $apiKey = '005385f3666cb67a6f99bc58b9a3e4b9';
        
        $weatherData = $this->getWeatherData($city, $apiKey);

        // Partagez les données avec toutes les vues
        View::share('city', $city);
        View::share('weatherData', $weatherData);

        return $next($request);
    }

    private function getWeatherData($city, $apiKey)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric', // Pour avoir les températures en Celsius
        ]);

        return $response->json();
    }
}
