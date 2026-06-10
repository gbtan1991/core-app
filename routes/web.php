<?php

use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Invitation\InvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

// Public home
Route::view('/', 'welcome')->name('home');

// Google OAuth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

// Invitation routes (guest only)
Route::middleware('guest')->prefix('invitation')->name('invitation.')->group(function () {
    Route::get('/{token}', [InvitationController::class, 'show'])->name('accept');
    Route::post('/{token}', [InvitationController::class, 'accept'])->name('accept.store');
});

// Admin routes (auth + verified + active)
Route::middleware(['auth', 'verified', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Staff Management (super_admin only)
    Route::middleware('role:super_admin')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/invite', [StaffController::class, 'create'])->name('invite');
        Route::post('/invite', [StaffController::class, 'store'])->name('invite.store');
        Route::get('/{staff}', [StaffController::class, 'show'])->name('show');
        Route::patch('/{staff}/toggle', [StaffController::class, 'toggle'])->name('toggle');
        Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
    });
});

// Legacy team-based dashboard (kept for team system compatibility)
Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', 'active', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

// Team invitations (auth required)
Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
});

require __DIR__.'/settings.php';
