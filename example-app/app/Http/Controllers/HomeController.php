<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubInfo;
use App\Models\FlashMessage;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
{
    $clubInfo = ClubInfo::first();
    $city = $clubInfo->city ?? 'Default City';
    $flashMessage = FlashMessage::latest()->first();
    
    $apiKey = '005385f3666cb67a6f99bc58b9a3e4b9';
    $weatherData = $this->getWeatherData($city, $apiKey);


    return view('welcome', compact('weatherData', 'city', 'flashMessage'));
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