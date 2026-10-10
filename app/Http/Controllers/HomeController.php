<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Formule;
use App\Models\Message;
use App\Models\Parametre;
use App\Models\Plat;
use App\Models\ZoneLivraison;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()->ordered()->withCount(['plats' => function ($query) {
            $query->where('disponible', true);
        }])->get();

        $platsPopulaires = Plat::with('category')
            ->disponible()
            ->populaire()
            ->take(8)
            ->get();

        $formules = Formule::disponible()->take(3)->get();
        $zones = ZoneLivraison::active()->get();

        return view('pages.home', compact('categories', 'platsPopulaires', 'formules', 'zones'));
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        $zones = ZoneLivraison::active()->get();
        return view('pages.contact', compact('zones'));
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'telephone' => 'nullable|string|max:30',
            'sujet' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        Message::create($validated);

        return back()->with('success', 'Votre message a bien été envoyé ! L’équipe Café Paris Lomé vous répondra dans les plus brefs délais.');
    }
}
