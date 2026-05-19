<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderCabinetController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get( '/', [HomeController::class, 'index'] )->name( 'home' );
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/booking/{id}/confirm', [RegistrationController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/{id}/store', [RegistrationController::class, 'store'])->name('booking.store');
    Route::post('/booking/{id}/cancel', [RegistrationController::class, 'cancel'])->name('booking.cancel');
});

Route::middleware(['auth', 'leader'])->group(function () {
    Route::get('/cabinet', [LeaderCabinetController::class, 'index'])->name('cabinet');

    Route::get('/master-class/create', [MasterClassController::class, 'create'])->name('master-class.create');
    Route::get('/master-class/busy-slots', [MasterClassController::class, 'busySlots'])->name('master-class.busy-slots');
    Route::post('/master-class/store', [MasterClassController::class, 'store'])->name('master-class.store');

    Route::get('/master-class/{id}', [LeaderCabinetController::class, 'show'])->name('master-class.show');

    Route::get('/master-class/{id}/edit', [MasterClassController::class, 'edit'])->name('master-class.edit');
    Route::post('/master-class/{id}/update', [MasterClassController::class, 'update'])->name('master-class.update');
});
