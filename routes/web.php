<?php

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
use App\Http\Controllers\PlayerFeminineController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\ChampionshipController;
use App\Http\Middleware\CheckRegistrationStatus;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\PlayerApplicationController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\RegisteredUserController;

// ══════════════════════════════════════════════════════════════════════
// PUBLIC
// ══════════════════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/teams', [PlayerController::class, 'publicRoster'])->name('teams');
Route::get('/calendar', [GameController::class, 'showCalendar'])->name('calendar.show');
Route::get('/news', [ArticleController::class, 'index'])->name('news');
Route::get('/about', [RegulationController::class, 'publicIndex'])->name('about.index');
Route::redirect('/clubinfo', '/news')->name('clubinfo');

// Fanshop / tribunes public view
Route::get('/fanshop', [TribuneController::class, 'index'])->name('fanshop.index');

// Article show by slug — exclude reserved words ('create') so admin route takes priority.
Route::get('/articles/{slug}', [ArticleController::class, 'show'])
    ->where('slug', '(?!create$)[A-Za-z0-9\-_]+')
    ->name('articles.show');

// Galleries public (list + show)
Route::get('/galleries', [GalleryController::class, 'publicIndex'])->name('galleries.public');
Route::get('/galleries/{gallery}', [GalleryController::class, 'show'])
    ->whereNumber('gallery')->name('galleries.show');

// Videos public
Route::get('/videos', [VideoController::class, 'publicIndex'])->name('videos.public');

// Interviews public
Route::get('/interviews', [InterviewController::class, 'publicIndex'])->name('interviews.index');
Route::get('/interviews/{slug}', [InterviewController::class, 'publicShow'])->name('interviews.show');

// Contact
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'sendEmail'])->name('contact.send');

// Player applications (public join form)
Route::get('/rejoindre', [PlayerApplicationController::class, 'create'])->name('rejoindre.create');
Route::post('/rejoindre', [PlayerApplicationController::class, 'store'])->name('rejoindre.store');

// Self-service RGPD / Loi 09-08
Route::get('/candidature/supprimer', [PlayerApplicationController::class, 'requestDeletion'])
    ->name('candidature.deletion.request');
Route::post('/candidature/supprimer', [PlayerApplicationController::class, 'sendDeletionLink'])
    ->name('candidature.deletion.send');
Route::get('/candidature/{token}', [PlayerApplicationController::class, 'showByToken'])
    ->where('token', '[A-Za-z0-9]{48}')
    ->name('candidature.show');
Route::delete('/candidature/{token}', [PlayerApplicationController::class, 'destroyByToken'])
    ->where('token', '[A-Za-z0-9]{48}')
    ->name('candidature.destroy');

// Legal
Route::get('/confidentialite', fn () => Inertia\Inertia::render('Legal/Privacy'))->name('legal.privacy');
Route::get('/legal', fn () => Inertia\Inertia::render('Legal/Mentions'))->name('legal');

// Payment (Stripe)
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/reservation/{id}/pdf', [PaymentController::class, 'downloadPDF'])->name('reservation.pdf');
Route::get('/download-pdf/{id}', [PaymentController::class, 'downloadPDF'])->name('download-pdf');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// i18n cookie
Route::post('/locale', function (\Illuminate\Http\Request $request) {
    $data = $request->validate(['locale' => 'required|in:fr,en,ar']);
    return response()->json(['ok' => true])->cookie('locale', $data['locale'], 60 * 24 * 365);
})->name('locale.set');

// ══════════════════════════════════════════════════════════════════════
// AUTH-PROTECTED (admin + user)
// ══════════════════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/settings', [UserSettingController::class, 'update'])->name('user.settings.update');

    // Dashboard actions
    Route::post('/dashboard/background-image', [DashboardController::class, 'storeBackgroundImage'])->name('dashboard.storeBackgroundImage');
    Route::delete('/dashboard/delete-background-image/{id}', [DashboardController::class, 'deleteBackgroundImage'])->name('dashboard.deleteBackgroundImage');
    Route::post('/dashboard/assign-background', [DashboardController::class, 'assignBackground'])->name('dashboard.assignBackground');
    Route::post('/dashboard/update-registration-status', [DashboardController::class, 'updateRegistrationStatus'])->name('dashboard.updateRegistrationStatus');
    Route::post('/users/store', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [DashboardController::class, 'destroyUser'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {

    // Settings (Club info + flash message)
    Route::get('/admin/settings', [ClubInfoController::class, 'index'])->name('admin.settings');
    Route::post('/dashboard/club-info', [ClubInfoController::class, 'store'])->name('club-info.store');
    Route::delete('/club-info/{field}/delete', [ClubInfoController::class, 'destroyField'])->name('club-info.destroyField');
    Route::put('/flashmessage/update', [HomeController::class, 'updateFlashMessage'])->name('flashmessage.update');
    Route::post('/welcome-image/store', [HomeController::class, 'storeWelcomeImage'])->name('welcome-image.store');

    // Squad
    Route::resource('players', PlayerController::class)->except(['show']);
    Route::resource('feminines', PlayerFeminineController::class)->except(['show'])
        ->parameters(['feminines' => 'feminine']);
    Route::resource('staff', StaffController::class)->except(['show']);
    Route::resource('coaches', CoachController::class)->except(['show']);

    // Sponsors + about-sections + regulations (full CRUD)
    Route::resource('sponsors', SponsorController::class)->except(['show']);
    Route::resource('about-sections', AboutSectionController::class)->parameters(['about-sections' => 'aboutSection'])->except(['show']);
    Route::resource('regulations', RegulationController::class)->except(['show']);
    Route::resource('championships', ChampionshipController::class)->except(['show']);

    // Media (admin URLs prefixed with /admin/ to avoid colliding with public)
    Route::get('/admin/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->whereNumber('video')->name('videos.edit');
    Route::match(['put', 'patch'], '/videos/{video}', [VideoController::class, 'update'])->whereNumber('video')->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->whereNumber('video')->name('videos.destroy');

    Route::get('/admin/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::get('/galleries/{gallery}/edit', [GalleryController::class, 'edit'])->whereNumber('gallery')->name('galleries.edit');
    Route::match(['put', 'patch'], '/galleries/{gallery}', [GalleryController::class, 'update'])->whereNumber('gallery')->name('galleries.update');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->whereNumber('gallery')->name('galleries.destroy');

    Route::resource('press_releases', PressReleaseController::class)->except(['show']);
    Route::resource('galleries.photos', PhotoController::class)->except(['show']);
    Route::post('/galleries/{gallery}/photos/store-multiple', [PhotoController::class, 'storeMultiple'])->name('galleries.photos.storeMultiple');

    // Articles admin (uses /articles/... but with numeric constraint to coexist with public /articles/{slug})
    Route::get('/articles', [ArticleController::class, 'adminIndex'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])
        ->whereNumber('article')->name('articles.edit');
    Route::match(['put', 'patch'], '/articles/{article}', [ArticleController::class, 'update'])
        ->whereNumber('article')->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])
        ->whereNumber('article')->name('articles.destroy');

    // Tribunes admin (public index is at /fanshop above)
    Route::get('/tribunes', [TribuneController::class, 'adminIndex'])->name('tribunes.index');
    Route::resource('tribunes', TribuneController::class)->except(['index', 'show']);

    // Teams admin (custom URL: /manage-teams)
    Route::get('/manage-teams', [TeamController::class, 'index'])->name('manage_teams.index');
    Route::get('/manage-teams/create', [TeamController::class, 'create'])->name('manage_teams.create');
    Route::post('/manage-teams', [TeamController::class, 'store'])->name('manage_teams.store');
    Route::get('/manage-teams/{team}/edit', [TeamController::class, 'edit'])->name('manage_teams.edit');
    Route::match(['put', 'patch'], '/manage-teams/{team}', [TeamController::class, 'update'])->name('manage_teams.update');
    Route::delete('/manage-teams/{team}', [TeamController::class, 'destroy'])->name('manage_teams.destroy');

    // Games admin
    Route::resource('games', GameController::class)->except(['show']);
    Route::post('/games/{game}/scores', [GameController::class, 'updateScores'])->name('games.updateScores');
    Route::post('/reset-scores', [GameController::class, 'resetScores'])->name('reset.scores');
    Route::post('/games/store-multiple', [GameController::class, 'storeMultiple'])->name('games.storeMultiple');
    Route::post('/championship/store', [GameController::class, 'storeChampionship'])->name('championship.store');
});

// Admin-prefixed section (interviews + applications kept for backward-compat)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/create', [InterviewController::class, 'create'])->name('interviews.create');
    Route::post('/interviews', [InterviewController::class, 'store'])->name('interviews.store');
    Route::get('/interviews/{interview:id}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
    Route::patch('/interviews/{interview:id}', [InterviewController::class, 'update'])->name('interviews.update');
    Route::delete('/interviews/{interview:id}', [InterviewController::class, 'destroy'])->name('interviews.destroy');

    Route::get('/applications', [PlayerApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [PlayerApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/cv', [PlayerApplicationController::class, 'streamCv'])->name('applications.cv');
    Route::patch('/applications/{application}/status', [PlayerApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    Route::delete('/applications/{application}', [PlayerApplicationController::class, 'destroy'])->name('applications.destroy');

    // Galleries admin — points to same controller, differentiated by URL
    Route::get('/galleries/{gallery}/photos', [PhotoController::class, 'index'])->name('galleries.photos');
});

// ══════════════════════════════════════════════════════════════════════
// AUTH (guest)
// ══════════════════════════════════════════════════════════════════════

Route::middleware(['guest', CheckRegistrationStatus::class])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Local dev proxy to prod for missing storage assets
if (app()->environment('local')) {
    Route::get('/storage/{path}', function (string $path) {
        return redirect('https://dinakenitrafc.ma/storage/' . $path, 302);
    })->where('path', '.*');
}

require __DIR__.'/auth.php';
