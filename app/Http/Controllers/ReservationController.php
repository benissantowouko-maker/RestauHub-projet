<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(): View
    {
        return view('pages.reservation');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:120',
            'telephone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'nombre_personnes' => 'required|integer|min:1|max:30',
            'date_reservation' => 'required|date|after_or_equal:today',
            'heure_reservation' => 'required|string',
            'occasion' => 'nullable|string|max:100',
            'remarques' => 'nullable|string|max:1000',
        ]);

        // Generate unique code RES-2026-XXXX
        $lastId = Reservation::max('id') ?? 0;
        $code = 'RES-2026-' . str_pad((string) ($lastId + 1), 4, '0', STR_PAD_LEFT);

        $reservation = Reservation::create([
            ...$validated,
            'code_reservation' => $code,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('reservation.confirmation', $reservation->code_reservation)
            ->with('success', 'Votre demande de réservation a été enregistrée avec succès !');
    }

    public function show(string $code): View
    {
        $reservation = Reservation::where('code_reservation', $code)->firstOrFail();
        return view('pages.reservation-confirmation', compact('reservation'));
    }
}
