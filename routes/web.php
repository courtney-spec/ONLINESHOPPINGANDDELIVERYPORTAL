<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ProductManagerDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ── Public home ──────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Auth Routes ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirect /dashboard to the correct role-based dashboard
Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->getDashboardRoute());
})->middleware('auth')->name('dashboard');
});

// ── Admin Routes ─────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Add more admin routes here: users, orders, reports, etc.
    });

// ── Product Manager Routes ────────────────────
Route::middleware(['auth', 'role:product_manager'])
    ->prefix('product-manager')
    ->name('product_manager.')
    ->group(function () {
        Route::get('/dashboard', [ProductManagerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

// ── Customer Routes ───────────────────────────
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        // Add more customer routes here: shop, cart, orders, etc.
    });

    

