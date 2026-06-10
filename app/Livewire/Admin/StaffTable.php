<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

/**
 * Reactive table of staff members with inline toggle and delete actions.
 *
 * Mounted on the Staff Management index page by super_admin only.
 */
class StaffTable extends Component
{
    /**
     * Render the staff table view.
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
     */
    public function toggle(int $userId): void
    {
        $user = User::where('id', $userId)->where('role', 'staff')->firstOrFail();
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Staff member {$status} successfully.");
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
}
