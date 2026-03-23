<?php
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\FairController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/fairs/create', [FairController::class, 'create'])->name('admin.fairs.create');
    Route::post('/fairs', [FairController::class, 'store'])->name('admin.fairs.store');
});
// Vendor Stall Kinar Route (Issue 12)
Route::post('/buy-stall', [VendorController::class, 'buyStall']);