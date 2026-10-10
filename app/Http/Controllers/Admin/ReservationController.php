<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $statut = $request->get('statut', 'tous');
        $date = $request->get('date');

        $query = Reservation::query();

        if ($statut && $statut !== 'tous') {
            $query->where('statut', $statut);
        }

        if ($date) {
            $query->whereDate('date_reservation', $date);
        }

        $reservations = $query->orderBy('date_reservation', 'desc')
            ->orderBy('heure_reservation', 'desc')
            ->paginate(15)
            ->withQueryString();

        $enAttenteCount = Reservation::where('statut', 'en_attente')->count();
        $aujourdhuiCount = Reservation::whereDate('date_reservation', Carbon::today())->count();

        return view('admin.reservations.index', compact('reservations', 'statut', 'date', 'enAttenteCount', 'aujourdhuiCount'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,confirmee,annulee,terminee',
            'table_numero' => 'nullable|string|max:50',
        ]);

        $reservation->update($validated);

        return back()->with('success', "Réservation #{$reservation->code_reservation} mise à jour avec succès.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return back()->with('success', 'Réservation supprimée.');
    }
}
