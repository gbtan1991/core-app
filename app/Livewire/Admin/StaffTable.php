<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\StaffAccountCreatedNotification;
use Livewire\Component;

/**
 * Reactive staff table with inline toggle, delete, and password-reset actions.
 *
 * Mounted on the Staff Management index page — super_admin only.
 */
class StaffTable extends Component
{
    /**
     * Render the staff table with a fresh query on every render.
     */
    public function render()
    {
        $staff = User::where('role', 'staff')
            ->with('inviter')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.admin.staff-table', compact('staff'));
    }

    /**
     * Toggle the is_active flag for a staff member.
     * Only valid for active accounts (not pending-invite).
     */
    public function toggle(int $userId): void
    {
        $user = User::where('id', $userId)->where('role', 'staff')->firstOrFail();

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "{$user->name} has been {$status}.");
    }

    /**
     * Generate a new temporary password, email it, and flag the user
     * to change it on next login.
     */
    public function resetPassword(int $userId): void
    {
        $user = User::where('id', $userId)->where('role', 'staff')->firstOrFail();

        $tempPassword = $this->generateTempPassword();

        $user->update([
            'password'             => $tempPassword,   // cast auto-hashes
            'must_change_password' => true,
        ]);

        $user->notify(new StaffAccountCreatedNotification($tempPassword, $user->name));

        session()->flash('success', "Password reset for {$user->name}. New credentials emailed.");
    }

    /**
     * Permanently delete a staff member.
     */
    public function delete(int $userId): void
    {
        $user = User::where('id', $userId)->where('role', 'staff')->firstOrFail();
        $name = $user->name ?: $user->email;
        $user->delete();

        session()->flash('success', "{$name} has been deleted.");
    }

    /**
     * Generate a 12-character alphanumeric temporary password.
     */
    private function generateTempPassword(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';

        return substr(str_shuffle(str_repeat($chars, 4)), 0, 12);
    }
}
