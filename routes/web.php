<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS Routes (Admin, Manager, Cashier)
    Route::middleware('role:admin,manager,cashier')->prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [SaleController::class, 'create'])->name('index');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
    });

    // Inventory Routes (Admin, Manager)
    Route::middleware('role:admin,manager')->prefix('inventory')->name('inventory.')->group(function () {
        // Products
        Route::resource('products', ProductController::class);
    });

    // Admin Routes (Admin only)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () { return view('admin.users.index'); })->name('users');
        Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
    });

    // Reports Routes (Admin, Manager)
    Route::middleware('role:admin,manager')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', function () { return view('reports.sales'); })->name('sales');
        Route::get('/inventory', function () { return view('reports.inventory'); })->name('inventory');
        Route::get('/profit-loss', function () { return view('reports.profit-loss'); })->name('profit-loss');
    });

    // Resource Routes for CRUD operations
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('products', \App\Http\Controllers\Inventory\ProductController::class);
        Route::resource('suppliers', \App\Http\Controllers\Inventory\SupplierController::class);
        Route::resource('customers', \App\Http\Controllers\CustomerController::class);
        Route::resource('sales', \App\Http\Controllers\POS\SaleController::class)->except(['create', 'store']);
        Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    });

    // Purchase Routes (Admin only)
    Route::middleware('role:admin')->prefix('purchases')->name('purchases.')->group(function () {
        Route::patch('/{purchase}/cancel', [\App\Http\Controllers\PurchaseController::class, 'cancel'])->name('cancel');
    });

    // POS Routes (Admin, Manager, Cashier)
    Route::middleware('role:admin,manager,cashier')->prefix('pos')->name('pos.')->group(function () {
        Route::get('/product', [\App\Http\Controllers\POS\SaleController::class, 'getProduct'])->name('getProduct');
        Route::patch('/sales/{sale}/cancel', [\App\Http\Controllers\POS\SaleController::class, 'cancel'])->name('sales.cancel');
    });
});

require __DIR__.'/auth.php';
