<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Livewire\Superadmin\Attendance;
// use Namu\WireChat\Http\Livewire\Chat;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::get('/super-admin/attendance', Attendance::class)->name('super.attendance');
    });

require __DIR__.'/auth.php';

