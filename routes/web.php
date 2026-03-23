<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('/unauthorized', 'unauthorized')->name('unauthorized');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            UserRole::Manager => redirect()->route('manager.dashboard'),
            UserRole::Hod => redirect()->route('hod.dashboard'),
            UserRole::Director => redirect()->route('director.dashboard'),
            UserRole::Admin => redirect()->route('admin.home'),
        };
    })->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Livewire::component('manager.dashboard', \App\Livewire\Manager\Dashboard::class);
    Livewire::component('manager.daily-entry', \App\Livewire\Manager\DailyEntry::class);
    Livewire::component('manager.history', \App\Livewire\Manager\History::class);

    Route::get('/manager/dashboard', \App\Livewire\Manager\Dashboard::class)->name('manager.dashboard');
    Route::get('/manager/daily-entry', \App\Livewire\Manager\DailyEntry::class)->name('manager.daily-entry');
    Route::get('/manager/history', \App\Livewire\Manager\History::class)->name('manager.history');
});

Route::middleware(['auth', 'verified', 'role:hod'])->group(function () {
    Route::get('/hod/dashboard', \App\Livewire\Hod\Dashboard::class)->name('hod.dashboard');
    Route::get('/hod/daily-entry', \App\Livewire\Hod\DailyEntry::class)->name('hod.daily-entry');
    Route::get('/hod/history', \App\Livewire\Hod\History::class)->name('hod.history');
    Route::get('/hod/big-rocks', \App\Livewire\Hod\BigRockManagement::class)->name('hod.big-rocks');
    Route::get('/hod/division-entries', \App\Livewire\Hod\DivisionEntries::class)->name('hod.division-entries');
    Route::get('/hod/division-summary', \App\Livewire\Hod\DivisionSummary::class)->name('hod.division-summary');
});

Route::middleware(['auth', 'verified', 'role:director'])->group(function () {
    Route::get('/director/dashboard', \App\Livewire\Director\Dashboard::class)->name('director.dashboard');
    Route::get('/director/company', \App\Livewire\Director\Company::class)->name('director.company');
    Route::get('/director/divisions', \App\Livewire\Director\Divisions::class)->name('director.divisions');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin', \App\Livewire\Admin\AdminHome::class)->name('admin.home');
    Route::get('/admin/users', \App\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('/admin/divisions', \App\Livewire\Admin\Divisions::class)->name('admin.divisions');
    Route::get('/admin/hod-assignment', \App\Livewire\Admin\HodAssignment::class)->name('admin.hod-assignment');
    Route::get('/admin/report-settings', \App\Livewire\Admin\ReportSettings::class)->name('admin.report-settings');
    Route::get('/admin/override', \App\Livewire\Admin\Override::class)->name('admin.override');
    Route::get('/admin/notifications', \App\Livewire\Admin\Notifications::class)->name('admin.notifications');
});

require __DIR__.'/settings.php';
