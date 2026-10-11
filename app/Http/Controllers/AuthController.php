<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Handles authentication for public clients only.
 * Staff members (admin, staff, livreur) must use /pro portal.
 */
class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

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

            if (! $user->actif) {
                Auth::logout();

                return back()->with('error', 'Votre compte a été désactivé. Contactez le support.');
            }

            // Staff must use the internal portal — silently reject here to avoid exposing /pro
            if (in_array($user->role, ['admin', 'staff', 'livreur'])) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Aucun compte client trouvé avec ces identifiants.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('compte.commandes'))
                ->with('success', 'Ravi de vous revoir, '.$user->name.' !');
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides. Vérifiez votre adresse email et mot de passe.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:120',
            'email'     => 'required|email|max:150|unique:users,email',
            'telephone' => 'required|string|max:30',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $validated['telephone'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'client',
            'actif'     => true,
        ]);

        Client::create([
            'user_id'          => $user->id,
            'nom'              => $user->name,
            'telephone'        => $user->telephone,
            'email'            => $user->email,
            'compte_utilisateur' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('menu.index')
            ->with('success', 'Votre compte a été créé avec succès ! Bon appétit !');
    }

    public function profile(): View
    {
        $user = Auth::user();
        $commandes = Commande::with(['lignes', 'facture'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('telephone_client', $user->telephone)
                      ->orWhere('email_client', $user->email);
            })
            ->latest()
            ->paginate(10);

        return view('pages.compte', compact('user', 'commandes'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Vous avez été déconnecté avec succès.');
    }
}
