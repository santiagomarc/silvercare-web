<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Routes users to correct dashboard based on user_type.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get authenticated user's profile to determine type
        $user = Auth::user();
        $profile = $user->profile;

        // Route based on user type
        if ($profile) {
            if ($profile->user_type === 'elderly') {
                // Check if profile is completed
                if (!$profile->profile_completed) {
                    return redirect()->route('profile.completion');
                }
                return redirect()->route('dashboard');
            } elseif ($profile->user_type === 'caregiver') {
                return redirect()->route('caregiver.dashboard');
            }
        }

        // Fallback to default dashboard if profile doesn't exist yet
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
