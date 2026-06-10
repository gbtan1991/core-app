<?php

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\AcceptInvitationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvitationController extends Controller
{
    /**
     * Show the invitation acceptance form.
     */
    public function show(string $token): View|RedirectResponse
    {
        $user = User::where('invitation_token', $token)
            ->whereNull('invitation_accepted_at')
            ->where('created_at', '>=', now()->subHours(48))
            ->first();

        if (! $user) {
            return view('invitation.accept', ['expired' => true, 'user' => null]);
        }

        return view('invitation.accept', ['expired' => false, 'user' => $user]);
    }

    /**
     * Process the accepted invitation and activate the account.
     */
    public function accept(AcceptInvitationRequest $request, string $token): RedirectResponse
    {
        $user = User::where('invitation_token', $token)
            ->whereNull('invitation_accepted_at')
            ->where('created_at', '>=', now()->subHours(48))
            ->first();

        if (! $user) {
            return redirect()->route('invitation.accept', $token)
                ->withErrors(['token' => 'This invitation link is invalid or has expired.']);
        }

        $user->update([
            'name' => $request->name,
            'password' => $request->password,
            'is_active' => true,
            'invitation_accepted_at' => now(),
            'invitation_token' => null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome! Your account has been activated.');
    }
}
