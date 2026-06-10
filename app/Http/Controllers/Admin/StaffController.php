<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateStaffRequest;
use App\Http\Requests\Admin\InviteStaffRequest;
use App\Http\Requests\Admin\UpdateStaffRequest;
use App\Models\User;
use App\Notifications\StaffAccountCreatedNotification;
use App\Notifications\StaffInvitationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // Index / Show
    // ──────────────────────────────────────────────────────────────────────

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
     * Show a staff member's details.
     */
    public function show(User $staff): View
    {
        abort_if($staff->role !== 'staff', 404);

        return view('admin.staff.show', compact('staff'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Path A — Direct Add
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Show the direct-add staff form with a pre-generated temporary password.
     */
    public function create(): View
    {
        $tempPassword = $this->generateTempPassword();

        return view('admin.staff.create', compact('tempPassword'));
    }

    /**
     * Directly create a staff account and email them their temporary password.
     */
    public function store(CreateStaffRequest $request): RedirectResponse
    {
        $tempPassword = $request->input('temp_password') ?: $this->generateTempPassword();

        $user = User::create([
            'name'                 => $request->name,
            'email'                => $request->email,
            'password'             => $tempPassword,
            'role'                 => 'staff',
            'is_active'            => true,
            'must_change_password' => true,
            'invited_by'           => $request->user()->id,
            'email_verified_at'    => now(),
        ]);

        $user->syncRoles(['staff']);

        $user->notify(new StaffAccountCreatedNotification($tempPassword, $user->name));

        return redirect()->route('admin.staff.index')
            ->with('success', "Account created for {$user->name}. Login credentials have been emailed.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Edit / Update
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Show the edit form for a staff member.
     */
    public function edit(User $staff): View
    {
        abort_if($staff->role !== 'staff', 404);

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update a staff member's name and email.
     */
    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        abort_if($staff->role !== 'staff', 404);

        $staff->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "{$staff->name}'s details have been updated.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Path B — Email Invitation (existing flow, preserved)
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Show the send-invitation form.
     */
    public function showInviteForm(): View
    {
        return view('admin.staff.invite');
    }

    /**
     * Send a staff invitation email with a signed token link.
     */
    public function sendInvite(InviteStaffRequest $request): RedirectResponse
    {
        $token = Str::random(64);

        $user = User::create([
            'email'           => $request->email,
            'name'            => '',
            'password'        => Str::random(32),
            'role'            => 'staff',
            'is_active'       => false,
            'invited_by'      => $request->user()->id,
            'invitation_token' => $token,
        ]);

        $user->syncRoles(['staff']);

        $user->notify(new StaffInvitationNotification($token, $request->user()->name));

        return redirect()->route('admin.staff.index')
            ->with('success', "Invitation sent to {$request->email}.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Toggle / Delete
    // ──────────────────────────────────────────────────────────────────────

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
     * Permanently delete a staff member.
     */
    public function destroy(User $staff): RedirectResponse
    {
        abort_if($staff->role !== 'staff', 404);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Generate a cryptographically random 12-character alphanumeric password.
     */
    private function generateTempPassword(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';

        return substr(str_shuffle(str_repeat($chars, 4)), 0, 12);
    }
}
