<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InviteStaffRequest;
use App\Models\User;
use App\Notifications\StaffInvitationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a list of all staff members.
     */
    public function index(): View
    {
        $staff = User::where('role', 'staff')
            ->with('inviter')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the invite staff form.
     */
    public function create(): View
    {
        return view('admin.staff.invite');
    }

    /**
     * Send a staff invitation email.
     */
    public function store(InviteStaffRequest $request): RedirectResponse
    {
        $token = Str::random(64);

        $user = User::create([
            'email' => $request->email,
            'name' => '',
            'password' => Str::random(32),
            'role' => 'staff',
            'is_active' => false,
            'invited_by' => $request->user()->id,
            'invitation_token' => $token,
        ]);

        $user->syncRoles(['staff']);

        $user->notify(new StaffInvitationNotification($token, $request->user()->name));

        return redirect()->route('admin.staff.index')
            ->with('success', "Invitation sent to {$request->email}.");
    }

    /**
     * Show a staff member's details.
     */
    public function show(User $staff): View
    {
        abort_if($staff->role !== 'staff', 404);

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Toggle a staff member's active status.
     */
    public function toggle(User $staff): RedirectResponse
    {
        abort_if($staff->role !== 'staff', 404);

        $staff->update(['is_active' => ! $staff->is_active]);

        $status = $staff->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Staff member {$status} successfully.");
    }

    /**
     * Delete a staff member.
     */
    public function destroy(User $staff): RedirectResponse
    {
        abort_if($staff->role !== 'staff', 404);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
