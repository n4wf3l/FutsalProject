<?php

use App\Models\UserSetting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AboutSectionController;
use App\Http\Controllers\ClubInfoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TribuneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PlayerU21Controller;
use App\Http\Controllers\RegulationController;
use App\Http\Middleware\CheckRegistrationStatus;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [PlayerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/settings', [UserSettingController::class, 'update'])->name('user.settings.update');
    Route::post('/dashboard/background-image', [DashboardController::class, 'storeBackgroundImage'])->name('dashboard.storeBackgroundImage');
    Route::delete('/dashboard/delete-background-image/{id}', [DashboardController::class, 'deleteBackgroundImage'])->name('dashboard.deleteBackgroundImage');
    Route::post('/dashboard/assign-background', [DashboardController::class, 'assignBackground'])->name('dashboard.assignBackground');
    Route::post('/dashboard/update-registration-status', [DashboardController::class, 'updateRegistrationStatus'])->name('dashboard.updateRegistrationStatus');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/users/store', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [DashboardController::class, 'destroyUser'])->name('users.destroy');
});

// Routes resource pour différents contrôleurs
Route::resource('players', PlayerController::class);
Route::resource('staff', StaffController::class);
Route::resource('coaches', CoachController::class);
Route::resource('sponsors', SponsorController::class);

// Routes pour les articles
Route::resource('articles', ArticleController::class)->except(['show']);

// Route pour afficher un article par son slug
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Routes pour About Section
Route::resource('about', AboutSectionController::class)->parameters([
    'about' => 'aboutSection'
]);

// Routes pour le Fanshop et les Tribunes
Route::get('/fanshop', [TribuneController::class, 'index'])->name('fanshop.index');
Route::resource('tribunes', TribuneController::class)->except(['index'])->middleware('auth');

// Autres routes
Route::get('/teams', [PlayerController::class, 'index'])->name('teams');
Route::get('/clubinfo', function () {
    return redirect()->route('news');
})->name('clubinfo');


Route::get('/news', [ArticleController::class, 'index'])->name('news');
Route::redirect('/clubinfo', '/news');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Routes pour la gestion du club et les paiements
Route::post('/dashboard/club-info', [ClubInfoController::class, 'store'])->name('club-info.store');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/reservation/{id}/pdf', [PaymentController::class, 'downloadPDF'])->name('reservation.pdf');
Route::get('/download-pdf/{id}', [PaymentController::class, 'downloadPDF'])->name('download-pdf');

// Routes pour la gestion des équipes (Vues)
Route::get('/manage-teams/create', [TeamController::class, 'create'])->name('manage_teams.create');
Route::get('/manage-teams/{team}/edit', [TeamController::class, 'edit'])->name('manage_teams.edit');
Route::put('/manage-teams/{team}', [TeamController::class, 'update'])->name('manage_teams.update');
Route::post('/manage-teams', [TeamController::class, 'store'])->name('manage_teams.store');
Route::delete('/manage-teams/{team}', [TeamController::class, 'destroy'])->name('manage_teams.destroy');
Route::get('/calendar', [GameController::class, 'showCalendar'])->name('calendar.show');
Route::post('/championship/store', [GameController::class, 'storeChampionship'])->name('championship.store');

// Route pour la gestion des scores des matchs
Route::post('games/{game}/scores', [GameController::class, 'updateScores'])->name('games.updateScores');
Route::post('/reset-scores', [GameController::class, 'resetScores'])->name('reset.scores');
Route::post('/games/store-multiple', [GameController::class, 'storeMultiple'])->name('games.storeMultiple');

// Gestion des matches (ressource complète pour le CRUD des matchs)
Route::resource('games', GameController::class)->except(['show']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::put('/flashmessage/update', [HomeController::class, 'updateFlashMessage'])->name('flashmessage.update');
Route::post('/welcome-image/store', [HomeController::class, 'storeWelcomeImage'])->name('welcome-image.store');

Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'sendEmail'])->name('contact.send');

Route::resource('press_releases', PressReleaseController::class);
Route::resource('galleries', GalleryController::class);
Route::resource('galleries.photos', PhotoController::class)->except(['show']);
Route::post('/galleries/{gallery}/photos/store-multiple', [PhotoController::class, 'storeMultiple'])->name('galleries.photos.storeMultiple');
Route::resource('playersu21', PlayerU21Controller::class);

Route::get('/about', [RegulationController::class, 'index'])->name('about.index');

Route::middleware('auth')->group(function () {
    Route::get('/regulations/create', [RegulationController::class, 'create'])->name('regulations.create');
    Route::post('/regulations', [RegulationController::class, 'store'])->name('regulations.store');
    Route::delete('/regulations/{regulation}', [RegulationController::class, 'destroy'])->name('regulations.destroy');
});

Route::middleware(['guest', CheckRegistrationStatus::class])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::resource('videos', VideoController::class);

Route::get('/legal', function () {
    return view('legal');
})->name('legal');

// Routes API
//Route::prefix('api')->group(function () {
//    Route::apiResource('games', ApiGameController::class);
//    Route::apiResource('teams', ApiTeamController::class);
//});

// Inclusion des routes d'authentification générées par Laravel
require __DIR__.'/auth.php';
