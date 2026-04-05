<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\FairController;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Global Dashboard Redirect (Line 25-27 update)
// 1. Global Redirect: Jei role-er user-i hok, /dashboard-e gele tar sothik jaygay niye jabe
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->role === 'vendor') {
        return redirect()->route('vendor.dashboard');
    }
    // Visitor ba Employee hole tar route-e redirect hobe
    return redirect()->route(auth()->user()->role . '.dashboard');
})->name('dashboard');


// Vendor Routes Group
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // ১. DASHBOARD: Ekhane shudhu "Welcome" message dekhabe (No $myStalls data)
    Route::get('/dashboard', function() { 
        return view('dashboard'); // Eita main dashboard portal pathabe
    })->name('dashboard');

    // ২. MY STALLS: Ekhane Transaction Table load hobe
    Route::get('/my-stalls', [VendorController::class, 'my_stalls'])->name('my_stalls');

    // Baki Routes...
    Route::get('/fairs', [VendorController::class, 'fairs'])->name('fairs');
    Route::get('/stalls/{fair_id}', [VendorController::class, 'stalls'])->name('stalls');
    Route::get('/api/stalls/{fair_id}', [VendorController::class, 'getAllStalls']);
    Route::post('/buy-stall', [VendorController::class, 'buyStall'])->name('buy_stall');
    Route::post('/buy-stalls-bulk', [VendorController::class, 'buyStallsBulk'])->name('buy_stalls_bulk');
});