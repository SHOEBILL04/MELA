<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\FairController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// --- Authentication (VUL TA EIKHANE CHILO, EKHON FIXED) ---
Route::middleware('guest')->group(function () {
    // Ei duita line ami vul kore kete diyechilam! Ekhon add korechi.
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Global Dashboard Redirect ---
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'vendor') return redirect()->route('vendor.dashboard');
    if ($role === 'employee') return redirect()->route('employee.dashboard');
    if ($role === 'visitor') return redirect()->route('visitor.dashboard');
    return abort(404);
})->name('dashboard');

// --- Vendor Routes ---
Route::prefix('vendor')->middleware(['auth', 'role:vendor'])->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-stalls', [VendorController::class, 'my_stalls'])->name('my_stalls');
    Route::post('/recruit/{id}', [VendorController::class, 'recruitEmployee'])->name('recruit');
    Route::get('/fairs', [VendorController::class, 'fairs'])->name('fairs');
    Route::get('/stalls/{fair_id}', [VendorController::class, 'stalls'])->name('stalls');
    Route::post('/buy-stall', [VendorController::class, 'buyStall'])->name('buy_stall');
    
    // Event Management Routes
    Route::get('/events', [VendorController::class, 'events'])->name('events');
    Route::post('/events', [VendorController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{id}/buyers', [VendorController::class, 'eventBuyers'])->name('events.buyers');
});

// --- Admin Routes ---
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [FairController::class, 'index'])->name('dashboard');
    Route::resource('fairs', FairController::class);
});

// --- Employee Routes ---
Route::prefix('employee')->middleware(['auth', 'role:employee'])->name('employee.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('employee.positions'); })->name('dashboard');
    Route::get('/positions', [EmployeeController::class, 'browsePositions'])->name('positions');
    Route::post('/position/apply/{id}', [EmployeeController::class, 'applyPosition'])->name('apply');
    Route::get('/history', [EmployeeController::class, 'viewHistory'])->name('history');
});

// --- Visitor Routes ---
Route::prefix('visitor')->middleware(['auth', 'role:visitor'])->name('visitor.')->group(function () {
    Route::get('/dashboard', function() { return redirect()->route('visitor.fairs'); })->name('dashboard');
    Route::get('/fairs', [VisitorController::class, 'browseFairs'])->name('fairs');
    Route::get('/events', [VisitorController::class, 'browseEvents'])->name('events');
    Route::post('/events/buy/{eventId}', [VisitorController::class, 'buyEventTicket'])->name('buyEventTicket');
    Route::get('/fairs/{fair_id}/days', [VisitorController::class, 'fairDays'])->name('fair_days');
    Route::post('/buy-fair-tickets-bulk', [VisitorController::class, 'buyFairTicketsBulk'])->name('buyFairTicketsBulk');
});