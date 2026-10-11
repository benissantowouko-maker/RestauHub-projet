<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     * Staff roles (admin, staff, livreur) are redirected to /pro when not authenticated.
     * Clients are redirected to /connexion.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            // Staff areas require the internal portal
            $staffRoles   = ['admin', 'staff', 'livreur'];
            $requiresStaff = ! empty(array_intersect($roles, $staffRoles));

            if ($requiresStaff) {
                return redirect()->route('staff.login')
                    ->with('warning', 'Veuillez vous connecter pour accéder à cet espace.');
            }

            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour accéder à cet espace.');
        }

        $user = Auth::user();

        if (! $user->actif) {
            Auth::logout();

            return redirect()->route('staff.login')
                ->with('error', 'Votre compte a été désactivé. Contactez l\'administrateur.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Accès non autorisé à cette section (Rôle requis : '.implode(', ', $roles).').');
    }
}
