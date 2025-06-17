<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->is_admin
        ? redirect('/admin/dashboard')
        : redirect('/book');
    }
    return redirect('/login');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }
        return redirect('/book'); 
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/book', [BookingController::class, 'index'])->name('book');
    Route::post('/book', [BookingController::class, 'store']);
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::delete('/reset-bookings/{show}', [AdminController::class, 'resetBookings'])->name('admin.resetBookings');
});

Route::get('/api/seats/{show}', [BookingController::class, 'getSeats']);
