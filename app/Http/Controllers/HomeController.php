<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubInfo;
use App\Models\Game;
use App\Models\Team;
use App\Models\FlashMessage;
use App\Models\Article;
use App\Models\WelcomeImage;
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

    $lastGame = Game::with(['homeTeam', 'awayTeam'])
        ->where('match_date', '<', now()->startOfDay())
        ->where(function($query) use ($clubPrefix) {
            $query->whereHas('homeTeam', function($q) use ($clubPrefix) {
                $q->where('name', 'LIKE', "$clubPrefix%");
            })
            ->orWhereHas('awayTeam', function($q) use ($clubPrefix) {
                $q->where('name', 'LIKE', "$clubPrefix%");
            });
        })
        ->orderBy('match_date', 'desc')
        ->first();

    $nextGames = Game::with(['homeTeam', 'awayTeam'])
        ->where('match_date', '>=', now()->startOfDay())
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
    $welcomeImage = WelcomeImage::latest()->first();
    $latestPhotos = Photo::latest()->take(8)->get();

    return Inertia::render('Home', [
        'clubName' => $clubName,
        'city' => $city,
        'clubLocation' => $clubLocation,
        'clubPrefix' => $clubPrefix,
        'logoPath' => $logoPath,
        'flashMessage' => $flashMessage,
        'lastGame' => $lastGame,
        'nextGames' => $nextGames,
        'articles' => $articles,
        'videos' => $videos,
        'welcomeImage' => $welcomeImage,
        'latestPhotos' => $latestPhotos,
        'weatherData' => $weatherData,
    ]);
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
            'homemessage' => 'nullable|string|max:255', // Valider homemessage
        ]);
    
        $flashMessage = FlashMessage::latest()->first() ?? new FlashMessage();
        $flashMessage->message = $request->input('flash_message');
        $flashMessage->homemessage = $request->input('homemessage'); // Enregistrer homemessage
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
