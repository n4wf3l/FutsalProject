<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubInfo;
use App\Models\Game;
use App\Models\Team;
use App\Models\FlashMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $clubInfo = ClubInfo::first();
        $city = $clubInfo->city ?? 'Default City';
        $clubLocation = $clubInfo->sportcomplex_location ?? 'Default Location';  
        $clubName = $clubInfo->club_name ?? 'Dina Kénitra FC'; // Assurez-vous que le nom du club est récupéré correctement
        $clubPrefix = substr($clubName, 0, 4); // Les 4 premières lettres du nom du club
    
        $flashMessage = FlashMessage::latest()->first();
        
        $apiKey = '005385f3666cb67a6f99bc58b9a3e4b9';
        $weatherData = $this->getWeatherData($city, $apiKey);
    
        // Récupérer le prochain match où le club est soit l'équipe à domicile, soit l'équipe à l'extérieur
        $nextGame = Game::where('match_date', '>=', now()->startOfDay())
            ->where(function($query) use ($clubPrefix) {
                $query->whereHas('homeTeam', function($q) use ($clubPrefix) {
                    $q->where('name', 'LIKE', "$clubPrefix%");
                })
                ->orWhereHas('awayTeam', function($q) use ($clubPrefix) {
                    $q->where('name', 'LIKE', "$clubPrefix%");
                });
            })
            ->orderBy('match_date', 'asc')
            ->first();
    
        return view('welcome', compact('weatherData', 'city', 'flashMessage', 'nextGame', 'clubLocation', 'clubPrefix', 'clubName'));
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

    public function updateFlashMessage(Request $request)
{
    // Valider l'entrée
    $request->validate([
        'flash_message' => 'required|string|max:255',
    ]);

    // Récupérer le dernier message flash ou en créer un nouveau s'il n'existe pas
    $flashMessage = FlashMessage::latest()->first() ?? new FlashMessage();

    // Mettre à jour le message flash
    $flashMessage->message = $request->input('flash_message');
    $flashMessage->save();

    return redirect()->back()->with('success', 'Flash message updated successfully!');
}
}