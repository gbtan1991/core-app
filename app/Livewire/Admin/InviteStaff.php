<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\StaffInvitationNotification;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

/**
 * Handles sending staff invitations via a validated email form.
 */
class InviteStaff extends Component
{
    /** @var string The email address to invite. */
    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    /**
     * Send the invitation and create a pending user record.
     */
    public function send(): void
    {
        $this->validate();

        $token = Str::random(64);

        $user = User::create([
            'name' => '',
            'email' => $this->email,
            'password' => Str::random(32),
            'role' => 'staff',
            'is_active' => false,
            'invited_by' => auth()->id(),
            'invitation_token' => $token,
        ]);

        $user->syncRoles(['staff']);

        $user->notify(new StaffInvitationNotification($token, auth()->user()->name));

        $this->reset('email');
        session()->flash('invite_success', "Invitation sent to {$user->email}.");
    }

    /**
     * Render the invite form view.
     */
    public function render()
    {
        return view('livewire.admin.invite-staff');
    }
}
