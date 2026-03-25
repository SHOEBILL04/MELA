<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\FairController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Global Dashboard Route (লগইন করার পর এখানে আসবে)
Route::middleware(['auth'])->get('/dashboard', function () {
    return view('dashboard'); 
})->name('dashboard');

// Visitor Routes
Route::middleware(['auth', 'role:visitor'])->prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('visitor.fairs'); })->name('dashboard');
    Route::get('/fairs', [VisitorController::class, 'browseFairs'])->name('fairs');
    Route::post('/fair/buy/{fairId}/{dayId}', [VisitorController::class, 'buyFairTicket'])->name('buyFairTicket');
    Route::get('/events', [VisitorController::class, 'browseEvents'])->name('events');
    Route::post('/event/buy/{eventId}', [VisitorController::class, 'buyEventTicket'])->name('buyEventTicket');
});

// Employee Routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('employee.positions'); })->name('dashboard');
    Route::get('/positions', [EmployeeController::class, 'browsePositions'])->name('positions');
    Route::post('/position/apply/{id}', [EmployeeController::class, 'applyPosition'])->name('apply');
    Route::get('/history', [EmployeeController::class, 'viewHistory'])->name('history');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/fairs/create', [FairController::class, 'create'])->name('admin.fairs.create');
    Route::post('/fairs', [FairController::class, 'store'])->name('admin.fairs.store');
});

// Vendor Routes
Route::post('/buy-stall', [VendorController::class, 'buyStall']);
Route::get('/buy-stall-page', function () {
    return view('buy_stall');
});
Route::get('/get-all-stalls', [App\Http\Controllers\VendorController::class, 'getAllStalls']);
Route::get('/dashboard', function () {
    return view('stall_dashboard');
});