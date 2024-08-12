<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserSettingController;
use App\Models\UserSetting;
use App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('players', PlayerController::class);
Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');


Route::get('/teams', [PlayerController::class, 'index'])->name('teams');
Route::get('/clubinfo', function () {
    return view('clubinfo');
})->name('clubinfo');

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');


// Routes CRUD pour le modèle Player


Route::get('/sponsors', function () {
    return view('sponsors');
})->name('sponsors');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/fanshop', function () {
    return view('fanshop');
})->name('fanshop');



Route::put('/settings', [UserSettingController::class, 'update'])->name('user.settings.update')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', function () {
    $user = Auth::user();
    $userSettings = UserSetting::where('user_id', $user->id)->first();
    
    return view('dashboard', compact('userSettings'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/players/{id}/edit', [PlayerController::class, 'edit'])->name('players.edit');

Route::resource('staff', StaffController::class);

require __DIR__.'/auth.php';
