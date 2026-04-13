<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Campus Lost & Found Tracker Routes
| These routes handle the main functionality of the application
|
*/

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Home page / Landing Page
Route::get('/', function() {
    return view('welcome');
})->name('home');

Route::get('/items', [ItemController::class, 'index'])->name('items.index');

Route::middleware('auth')->group(function () {
    Route::get('/database', [DatabaseDashboardController::class, 'index'])->name('database.index');

    // Show form to create new item
    Route::get('/create', [ItemController::class, 'create'])->name('items.create');

    // Store new item in database (Protected by rate limiting: max 5 requests per minute)
    Route::post('/store', [ItemController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('items.store');

    // Show form to edit an item
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');

    // Update an existing item
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');

    // Mark item as claimed
    Route::patch('/items/{item}/claim', [ItemController::class, 'claim'])->name('items.claim');

    // Delete an item
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});

// Show single item details (placed outside auth so anyone can see details)
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
