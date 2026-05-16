<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerCheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\ProductManagerDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminDeliveryController;
use App\Http\Controllers\CustomerDeliveryController;
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
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
        Route::get('/reports/download', [AdminDashboardController::class, 'downloadReport'])->name('reports.download');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('/deliveries/create', [AdminDeliveryController::class, 'create'])->name('deliveries.create');
        Route::post('/deliveries', [AdminDeliveryController::class, 'store'])->name('deliveries.store');
        Route::get('/deliveries/{delivery}', [AdminDeliveryController::class, 'show'])->name('deliveries.show');
        Route::get('/deliveries/{delivery}/edit', [AdminDeliveryController::class, 'edit'])->name('deliveries.edit');
        Route::patch('/deliveries/{delivery}', [AdminDeliveryController::class, 'update'])->name('deliveries.update');
        
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
            Route::get('/cart', [CustomerCartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CustomerCartController::class, 'store'])->name('cart.store');
        Route::patch('/cart/{product}', [CustomerCartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{product}', [CustomerCartController::class, 'destroy'])->name('cart.destroy');
        Route::get('/checkout', [CustomerCheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/checkout', [CustomerCheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/track', [CustomerDeliveryController::class, 'track'])->name('orders.track');
    });

// ── Auth Routes ──────────────────────────────
require __DIR__ . '/auth.php';


