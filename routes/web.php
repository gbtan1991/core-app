<?php

use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordChangeController;
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

// Invitation routes (guest only) — Path B acceptance
Route::middleware('guest')->prefix('invitation')->name('invitation.')->group(function () {
    Route::get('/{token}', [InvitationController::class, 'show'])->name('accept');
    Route::post('/{token}', [InvitationController::class, 'accept'])->name('accept.store');
});

// Forced password change — requires auth but NOT must_change_password middleware
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

// Admin routes (auth + verified + active + must_change_password)
Route::middleware(['auth', 'verified', 'active', 'must_change_password'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Staff Management (super_admin only)
    Route::middleware('role:super_admin')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');

        // Path A — Direct add
        Route::get('/create', [StaffController::class, 'create'])->name('create');
        Route::post('/', [StaffController::class, 'store'])->name('store');

        // Path B — Email invitation
        Route::get('/invite', [StaffController::class, 'showInviteForm'])->name('invite');
        Route::post('/invite', [StaffController::class, 'sendInvite'])->name('invite.store');

        // Edit / Update
        Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
        Route::patch('/{staff}', [StaffController::class, 'update'])->name('update');

        // Show / Toggle / Delete
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
