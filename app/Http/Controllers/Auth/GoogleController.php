<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     *
     * Only allows login if the email already exists. No self-registration.
     */
    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'No account found. Please contact your administrator.']);
        }

        if (! $user->is_active) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact your administrator.']);
        }

        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::login($user, remember: true);

        return redirect($this->redirectPathForRole($user));
    }

    /**
     * Determine the post-login redirect path based on the user's role.
     */
    private function redirectPathForRole(User $user): string
    {
        return match ($user->role) {
            'super_admin', 'staff' => route('admin.dashboard'),
            default => route('admin.dashboard'),
        };
    }
}
