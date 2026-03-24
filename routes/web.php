<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\FairController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    Route::get('/fairs/create', [FairController::class, 'create'])->name('admin.fairs.create');
    Route::post('/fairs', [FairController::class, 'store'])->name('admin.fairs.store');
});


// Issue 12: Stall Purchase System
Route::get('/buy-stall-page', function () {
    return view('buy_stall');
});
Route::post('/buy-stall', [VendorController::class, 'buyStall']);


// Issue 13: Employee Recruitment (Job Post)
Route::get('/post-job', function () { 
    return view('post_job'); 
});
// Ekhane theke withoutMiddleware (CSRF bypass) remove kore diyechi karon amra proper system use korchi
Route::post('/api/create-job', [VendorController::class, 'postEmployeePosition']);