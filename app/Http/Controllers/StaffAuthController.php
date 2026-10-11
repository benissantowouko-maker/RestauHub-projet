<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles authentication for internal staff members only.
 * This portal is intentionally unlisted and not linked from the public site.
 * Only roles: admin, staff (cuisine), livreur are accepted.
 */
class StaffAuthController extends Controller
{
    /** Display the hidden staff login page. */
    public function showLogin(): View
    {
        if (Auth::check()) {
            return redirect()->to($this->staffRedirectUrl(Auth::user()->role));
        }

        return view('staff.login');
    }

    /** Authenticate a staff member (admin / staff / livreur only). */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Reject if account is disabled
            if (! $user->actif) {
                Auth::logout();

                return back()->with('error', 'Votre compte a été désactivé. Contactez l\'administrateur.');
            }

            // Reject clients who accidentally land here
            if ($user->role === 'client') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Accès réservé au personnel interne.',
                ])->onlyInput('email');
            }

            return redirect()->to($this->staffRedirectUrl($user->role))
                ->with('success', 'Bienvenue, '.$user->name.' !');
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ])->onlyInput('email');
    }

    /** Logout and redirect to the staff portal. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')->with('info', 'Vous avez été déconnecté.');
    }

    /** Resolve the dashboard URL for a given role. */
    private function staffRedirectUrl(string $role): string
    {
        return match ($role) {
            'admin'   => route('admin.dashboard'),
            'staff'   => route('cuisine.dashboard'),
            'livreur' => route('coursier.dashboard'),
            default   => route('staff.login'),
        };
    }
}
