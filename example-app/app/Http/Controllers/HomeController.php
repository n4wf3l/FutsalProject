<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubInfo;
use App\Models\Game;
use App\Models\Team;
use App\Models\FlashMessage;
use App\Models\Article;
use App\Models\WelcomeImage; // Importez le modèle WelcomeImage
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        $clubInfo = ClubInfo::first();
        $city = $clubInfo->city ?? 'Default City';
        $clubLocation = $clubInfo->sportcomplex_location ?? 'Default Location';  
        $clubName = $clubInfo->club_name ?? 'Dina Kénitra FC';
        $clubPrefix = substr($clubName, 0, 4);
        $logoPath = $clubInfo->logo_path ?? null;
        $flashMessage = FlashMessage::latest()->first();
        
        $apiKey = '005385f3666cb67a6f99bc58b9a3e4b9';
        $weatherData = $this->getWeatherData($city, $apiKey);
    
        // Récupérer le prochain match unique
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
    
        // Récupérer les cinq prochains matchs maximum
        $nextGames = Game::where('match_date', '>=', now()->startOfDay())
            ->where(function($query) use ($clubPrefix) {
                $query->whereHas('homeTeam', function($q) use ($clubPrefix) {
                    $q->where('name', 'LIKE', "$clubPrefix%");
                })
                ->orWhereHas('awayTeam', function($q) use ($clubPrefix) {
                    $q->where('name', 'LIKE', "$clubPrefix%");
                });
            })
            ->orderBy('match_date', 'asc')
            ->take(5)
            ->get();
    
        $articles = Article::latest()->take(4)->get();
        $videos = Video::latest()->take(2)->get();
    
        // Récupérer la dernière image ajoutée
        $welcomeImage = WelcomeImage::latest()->first();
    
        // Récupérer les 8 dernières images de la galerie
        $latestPhotos = Photo::latest()->take(8)->get();
    
        return view('welcome', compact('weatherData', 'city', 'flashMessage', 'nextGame', 'nextGames', 'clubLocation', 'clubPrefix', 'clubName', 'articles', 'videos', 'welcomeImage', 'logoPath', 'latestPhotos'));
    }
    
    private function getWeatherData($city, $apiKey)
    {
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    public function updateFlashMessage(Request $request)
    {
        $request->validate([
            'flash_message' => 'required|string|max:255',
        ]);

        $flashMessage = FlashMessage::latest()->first() ?? new FlashMessage();
        $flashMessage->message = $request->input('flash_message');
        $flashMessage->save();

        return redirect()->back()->with('success', 'Flash message updated successfully!');
    }

    public function storeWelcomeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png|max:2048',
        ]);

        $path = $request->file('image')->store('welcome_images', 'public');

        WelcomeImage::create([
            'image_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Image added successfully!');
    }
}
