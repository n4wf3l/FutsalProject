<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserSettingController;
use App\Models\UserSetting;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AboutSectionController;
use App\Http\Controllers\ClubInfoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TribuneController;
use App\Http\Controllers\PaymentController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes protégées par middleware auth et verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/settings', [UserSettingController::class, 'update'])->name('user.settings.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
});

// Routes resource pour différents contrôleurs
Route::resource('players', PlayerController::class);
Route::resource('staff', StaffController::class);
Route::resource('coaches', CoachController::class);
Route::resource('sponsors', SponsorController::class);

// Routes pour les articles
Route::resource('articles', ArticleController::class)->except(['show'])->middleware('auth');

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
Route::get('/clubinfo', [ArticleController::class, 'index'])->name('clubinfo');
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Routes pour la gestion du club et les paiements
Route::post('/dashboard/club-info', [ClubInfoController::class, 'store'])->name('club-info.store');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/reservation/{id}/pdf', [PaymentController::class, 'downloadPDF'])->name('reservation.pdf');

// Inclusion des routes d'authentification générées par Laravel
require __DIR__.'/auth.php';
