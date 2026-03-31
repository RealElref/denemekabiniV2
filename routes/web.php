<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Paket seçimi
Route::get('/paket/{slug}', [PackageController::class, 'select'])->name('package.select');

// Auth - giriş yapmamış kullanıcılar
Route::middleware('guest')->group(function () {
    Route::get('/giris', [LoginController::class, 'showForm'])->name('login');
    Route::post('/giris', [LoginController::class, 'login']);
    Route::get('/kayit', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/kayit', [RegisterController::class, 'register']);
});

// Sadece giriş yapmış kullanıcılar
Route::middleware('auth')->group(function () {
    Route::post('/cikis', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'guest-only'])->group(function () {
    Route::get('/panel', [DashboardController::class, 'index'])->name('dashboard');
    
    // Önce sabit route'lar
    Route::get('/odeme/basarili', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/odeme/basarisiz', [PaymentController::class, 'failed'])->name('payment.failed');

    Route::get('/panel', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/panel/kredi-al', [\App\Http\Controllers\CustomCreditController::class, 'show'])->name('dashboard.credits');
    Route::post('/panel/kredi-al', [\App\Http\Controllers\CustomCreditController::class, 'process'])->name('dashboard.credits.process');

    
    // Sonra dinamik route
    Route::get('/odeme/{slug}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/odeme/{slug}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/odeme/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook')->withoutMiddleware(['auth', 'guest-only']);
});


Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['tr', 'en'])) {
        session(['locale' => $locale]);
    }
    $referer = request()->headers->get('referer', '/');
    return redirect($referer);
})->name('lang.switch');

// API Endpoints for Dashboard SPA (Session Auth, No Sanctum needed)
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/generations', [\App\Http\Controllers\GenerationController::class, 'store']);
    Route::get('/generations/{id}/status', [\App\Http\Controllers\GenerationController::class, 'status']);
    Route::delete('/generations/{id}', [\App\Http\Controllers\GenerationController::class, 'destroy']);

    Route::post('/domains', [\App\Http\Controllers\DomainController::class, 'store']);
    Route::delete('/domains/{id}', [\App\Http\Controllers\DomainController::class, 'destroy']);
});