<?php

use App\Filament\Pages\BookingReport;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/reports/bookings', [ReportController::class, 'bookingSummary'])->name('reports.bookings');
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
Route::post('/admin/reports/bookings/download', [BookingReport::class, 'download'])
    ->name('filament.pages.reports.bookings.download');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

require __DIR__.'/auth.php';
