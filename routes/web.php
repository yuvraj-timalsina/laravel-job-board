<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::resource('listings', ListingController::class);

Route::get('/{listing}/apply', [ListingController::class, 'apply'])->name('listing.apply');
