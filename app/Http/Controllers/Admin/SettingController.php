<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use App\Models\User;
use App\Models\ZoneLivraison;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $parametres = Parametre::all()->pluck('valeur', 'cle');
        $zones = ZoneLivraison::all();
        $utilisateurs = User::whereIn('role', ['admin', 'staff', 'livreur'])->get();

        return view('admin.parametres.index', compact('parametres', 'zones', 'utilisateurs'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $fields = [
            'nom_restaurant',
            'slogan',
            'telephone_principal',
            'telephone_secondaire',
            'email_contact',
            'adresse',
            'horaires',
            'numero_tmoney',
            'numero_flooz',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Parametre::set($field, $request->input($field));
            }
        }

        return back()->with('success', 'Informations générales mises à jour.');
    }

    // Zone management
    public function storeZone(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom_zone' => 'required|string|max:100',
            'frais_fcfa' => 'required|numeric|min:0',
            'delai_estime_min' => 'required|integer|min:5',
            'description' => 'nullable|string|max:255',
        ]);

        ZoneLivraison::create([
            ...$validated,
            'active' => true,
        ]);

        return back()->with('success', 'Zone de livraison ajoutée avec succès.');
    }

    public function updateZone(Request $request, int $id): RedirectResponse
    {
        $zone = ZoneLivraison::findOrFail($id);
        $validated = $request->validate([
            'nom_zone' => 'required|string|max:100',
            'frais_fcfa' => 'required|numeric|min:0',
            'delai_estime_min' => 'required|integer|min:5',
            'description' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $zone->update([
            ...$validated,
            'active' => $request->has('active'),
        ]);

        return back()->with('success', 'Zone de livraison mise à jour.');
    }

    public function destroyZone(int $id): RedirectResponse
    {
        $zone = ZoneLivraison::findOrFail($id);
        $zone->delete();

        return back()->with('success', 'Zone de livraison supprimée.');
    }

    // User management (Staff & Couriers)
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150|unique:users,email',
            'telephone' => 'required|string|max:30',
            'role' => 'required|in:admin,staff,livreur',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'actif' => true,
        ]);

        return back()->with('success', "Compte {$validated['role']} créé pour {$validated['name']}.");
    }

    public function toggleUserStatus(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte administrateur.');
        }

        $user->actif = ! $user->actif;
        $user->save();

        return back()->with('success', "Compte de {$user->name} " . ($user->actif ? 'activé' : 'désactivé') . '.');
    }
}
