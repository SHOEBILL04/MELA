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

// Global Dashboard Route
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

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/fairs', [FairController::class, 'index'])->name('admin.fairs.index');
    Route::get('/fairs/create', [FairController::class, 'create'])->name('admin.fairs.create');
    Route::post('/fairs', [FairController::class, 'store'])->name('admin.fairs.store');
    Route::get('/fairs/{id}', [FairController::class, 'show'])->name('admin.fairs.show');

    Route::get('/bids', [\App\Http\Controllers\Admin\BidController::class, 'index'])->name('admin.bids.index');
    Route::post('/bids/{id}/approve', [\App\Http\Controllers\Admin\BidController::class, 'approve'])->name('admin.bids.approve');
    Route::post('/bids/{id}/reject', [\App\Http\Controllers\Admin\BidController::class, 'reject'])->name('admin.bids.reject');
});

// Vendor Routes
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/fairs', [VendorController::class, 'fairs'])->name('fairs');
    Route::get('/stalls/{fair_id}', [VendorController::class, 'stalls'])->name('stalls');
    Route::get('/api/stalls/{fair_id}', [VendorController::class, 'getAllStalls']);
    Route::get('/buy-stall-page', [VendorController::class, 'buyStallPage'])->name('buy_stall_page');
    Route::post('/buy-stall', [VendorController::class, 'buyStall'])->name('buy_stall');
});