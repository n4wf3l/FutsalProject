<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\UserSetting;
use App\Models\ClubInfo;
use App\Models\BackgroundImage;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\AccessCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Player;
use App\Models\Staff;
use App\Models\Coach;
use App\Models\Tribune;
use App\Models\PlayerU21;
use App\Models\Article;
use App\Models\Game;
use App\Models\Video;
use App\Models\Photo;
use App\Models\PlayerApplication;
use App\Models\Interview;
use Illuminate\Support\Facades\Schema;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->startOfDay();

        // Defensive: /admin/applications & /admin/interviews depend on tables
        // that may not exist yet in some environments (fresh clone, migration
        // still pending).
        $hasApplications = Schema::hasTable('player_applications');
        $hasInterviews = Schema::hasTable('interviews');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'players' => Player::count(),
                'playersU21' => PlayerU21::count(),
                'staff' => Staff::count(),
                'coaches' => Coach::count(),
                'games' => Game::count(),
                'articles' => Article::count(),
                'videos' => Video::count(),
                'photos' => Photo::count(),
                'tribunes' => Tribune::count(),
                'users' => User::count(),
                'upcomingGames' => Game::where('match_date', '>=', $today)->count(),
                'pastGames' => Game::where('match_date', '<', $today)->count(),
            ],
            'inbox' => [
                'applicationsPending' => $hasApplications
                    ? PlayerApplication::where('status', PlayerApplication::STATUS_PENDING)->count()
                    : 0,
                'interviewsDraft' => $hasInterviews
                    ? Interview::whereNull('published_at')->count()
                    : 0,
            ],
            'pendingApplications' => $hasApplications
                ? PlayerApplication::where('status', PlayerApplication::STATUS_PENDING)
                    ->latest()
                    ->take(4)
                    ->get(['id', 'first_name', 'last_name', 'email', 'category', 'created_at'])
                : collect(),
            'draftInterviews' => $hasInterviews
                ? Interview::whereNull('published_at')
                    ->latest()
                    ->take(3)
                    ->get(['id', 'title', 'interviewee_name', 'interviewee_role', 'updated_at'])
                : collect(),
            'recentArticles' => Article::latest()->take(4)->get(),
            'upcomingGames' => Game::with(['homeTeam', 'awayTeam'])
                ->where('match_date', '>=', $today)
                ->orderBy('match_date', 'asc')
                ->take(3)
                ->get(),
            'recentUsers' => User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
            'registrationOpen' => config('app.registration_open', false),
        ]);
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
    
        // Sauvegarder l'email dans le modèle ClubInfo
        $clubInfo = ClubInfo::first();
        if (!$clubInfo) {
            $clubInfo = new ClubInfo();
        }
        $clubInfo->email = $request->input('email');
        $clubInfo->save();
    
        return redirect()->back()->with('status', 'Settings updated successfully!');
    }

    public function storeBackgroundImage(Request $request)
{
    // Validation du fichier d'image
    $request->validate([
        'background_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'assigned_page' => 'nullable|string',
    ]);

    // Gestion du téléchargement de l'image
    $imagePath = $request->file('background_image')->store('background_images', 'public');

    // Si une page est assignée, désassigner toutes les autres images de cette page
    if ($request->assigned_page) {
        \App\Models\BackgroundImage::where('assigned_page', $request->assigned_page)
            ->update(['assigned_page' => null]);
    }

    // Créer une nouvelle entrée pour l'image avec la page assignée
    \App\Models\BackgroundImage::create([
        'image_path' => $imagePath,
        'assigned_page' => $request->assigned_page,
    ]);

    return redirect()->back()->with('success', 'Image de fond ajoutée et assignée avec succès.');
}

    public function deleteBackgroundImage($id)
    {
        $image = BackgroundImage::findOrFail($id);

        // Supprimer le fichier physique
        Storage::delete('public/' . $image->image_path);

        // Supprimer l'entrée de la base de données
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function assignBackground(Request $request)
    {
        $image = BackgroundImage::find($request->image_id);
    
        if ($image) {
            // Si une page est sélectionnée
            if ($request->page) {
                // Désassigner toutes les autres images de cette page
                BackgroundImage::where('assigned_page', $request->page)
                    ->update(['assigned_page' => null]);
    
                // Assigner l'image à la page sélectionnée
                $image->assigned_page = $request->page;
            } else {
                // Si "Aucune page" est sélectionnée, désassigner l'image
                $image->assigned_page = null;
            }
    
            $image->save();
        }
    
        return redirect()->back()->with('success', 'L\'image de fond a été mise à jour avec succès.');
    }


    public function updateRegistrationStatus(Request $request)
    {
        Log::info('Starting registration status update...');
        
        try {
            $request->validate([
                'registration_open' => 'required|boolean',
            ]);
    
            $registrationOpen = filter_var($request->input('registration_open'), FILTER_VALIDATE_BOOLEAN);
    
            Log::info('Registration status: ' . ($registrationOpen ? 'true' : 'false'));
    
            // Mettre à jour la valeur dans le fichier .env
            $this->setEnvironmentValue('REGISTRATION_OPEN', $registrationOpen ? 'true' : 'false');
    
            Log::info('Environment value set successfully.');
    
            return redirect()->route('dashboard')->with('success', 'Registration status updated successfully.');
    
        } catch (\Exception $e) {
            Log::error('Error updating registration status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update registration status.');
        }
    }

    protected function setEnvironmentValue($key, $value)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        // Lire le contenu du fichier .env
        $envContent = file_get_contents($path);
        
        // Trouver la ligne contenant la clé
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            // Remplacer la valeur existante
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            // Ajouter la nouvelle clé si elle n'existe pas
            $envContent .= "\n{$key}={$value}";
        }

        // Écrire les modifications dans le fichier .env
        file_put_contents($path, $envContent);
    }

    // Recharger les variables d'environnement
    Artisan::call('config:cache');
}

public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('dashboard')->with('success', 'User created successfully.');
}

public function destroyUser($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('dashboard')->with('success', 'User deleted successfully.');
}

protected function getReservationsFromFile()
{
    $filePath = storage_path('app/reservations.json');

    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        return json_decode($json, true) ?? [];
    }

    return [];
}
}
