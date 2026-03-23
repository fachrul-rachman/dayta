<?php

use App\Enums\UserRole;
use App\Livewire\Admin\AdminHome;
use App\Livewire\Admin\HodAssignment;
use App\Livewire\Admin\Notifications;
use App\Livewire\Admin\Override;
use App\Livewire\Admin\ReportSettings;
use App\Livewire\Admin\Users;
use App\Livewire\Director\Company;
use App\Livewire\Director\Divisions;
use App\Livewire\Hod\BigRockManagement;
use App\Livewire\Hod\DivisionEntries;
use App\Livewire\Hod\DivisionSummary;
use App\Livewire\Manager\DailyEntry;
use App\Livewire\Manager\Dashboard;
use App\Livewire\Manager\History;
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
    Livewire::component('manager.dashboard', Dashboard::class);
    Livewire::component('manager.daily-entry', DailyEntry::class);
    Livewire::component('manager.history', History::class);

    Route::get('/manager/dashboard', Dashboard::class)->name('manager.dashboard');
    Route::get('/manager/daily-entry', DailyEntry::class)->name('manager.daily-entry');
    Route::get('/manager/history', History::class)->name('manager.history');
});

Route::middleware(['auth', 'verified', 'role:hod'])->group(function () {
    Route::get('/hod/dashboard', App\Livewire\Hod\Dashboard::class)->name('hod.dashboard');
    Route::get('/hod/daily-entry', App\Livewire\Hod\DailyEntry::class)->name('hod.daily-entry');
    Route::get('/hod/history', App\Livewire\Hod\History::class)->name('hod.history');
    Route::get('/hod/big-rocks', BigRockManagement::class)->name('hod.big-rocks');
    Route::get('/hod/division-entries', DivisionEntries::class)->name('hod.division-entries');
    Route::get('/hod/division-summary', DivisionSummary::class)->name('hod.division-summary');
});

Route::middleware(['auth', 'verified', 'role:director'])->group(function () {
    Route::get('/director/dashboard', App\Livewire\Director\Dashboard::class)->name('director.dashboard');
    Route::get('/director/company', Company::class)->name('director.company');
    Route::get('/director/divisions', Divisions::class)->name('director.divisions');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin', AdminHome::class)->name('admin.home');
    Route::get('/admin/users', Users::class)->name('admin.users');
    Route::get('/admin/divisions', App\Livewire\Admin\Divisions::class)->name('admin.divisions');
    Route::get('/admin/hod-assignment', HodAssignment::class)->name('admin.hod-assignment');
    Route::get('/admin/report-settings', ReportSettings::class)->name('admin.report-settings');
    Route::get('/admin/override', Override::class)->name('admin.override');
    Route::get('/admin/notifications', Notifications::class)->name('admin.notifications');

    Route::get('/admin/divisions/import-template', function () {
        $path = public_path('excel/Template Form Divisi.xlsx');

        abort_unless(is_file($path), 404);

        return response()->download($path, 'Template Form Divisi.xlsx');
    })->name('admin.divisions.import-template');

    Route::get('/admin/users/import-template', function () {
        $path = public_path('excel/Template Form User.xlsx');

        abort_unless(is_file($path), 404);

        return response()->download($path, 'Template Form User.xlsx');
    })->name('admin.users.import-template');
});

require __DIR__.'/settings.php';
