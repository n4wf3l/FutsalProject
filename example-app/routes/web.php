<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserSettingController;
use App\Models\UserSetting;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\SponsorController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AboutSectionController;
use App\Http\Controllers\ClubInfoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/settings', [UserSettingController::class, 'update'])->name('user.settings.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
});

Route::resource('players', PlayerController::class);
Route::resource('staff', StaffController::class);
Route::resource('coaches', CoachController::class);
Route::resource('sponsors', SponsorController::class)->middleware('auth');
Route::resource('articles', ArticleController::class)->middleware('auth');
Route::resource('about', AboutSectionController::class)->parameters([
    'about' => 'aboutSection'
]);

Route::get('/teams', [PlayerController::class, 'index'])->name('teams');
Route::get('/clubinfo', [ArticleController::class, 'index'])->name('clubinfo');
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/fanshop', function () {
    return view('fanshop');
})->name('fanshop');

Route::post('/dashboard/club-info', [ClubInfoController::class, 'store'])->name('club-info.store');;

require __DIR__.'/auth.php';
