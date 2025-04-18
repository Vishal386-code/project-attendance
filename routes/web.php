<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Livewire\Superadmin\Attendance;
use App\Livewire\Superadmin\TechnoHolidayForm;
use App\Livewire\Superadmin\ProfileForm;
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
        Route::get('/super-admin/holidays', TechnoHolidayForm::class)->name('super.holidays');
        Route::get('/super-admin/detail', ProfileForm::class)->name('super.detail');
    });

require __DIR__.'/auth.php';

