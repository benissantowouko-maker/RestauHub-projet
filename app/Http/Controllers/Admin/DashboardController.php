<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Message;
use App\Models\Plat;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Financial KPIs
        $revenusAujourdhui = Commande::whereDate('created_at', $today)
            ->where('statut', '!=', 'annulee')
            ->sum('montant_total');

        $revenusSemaine = Commande::whereBetween('created_at', [$startOfWeek, Carbon::now()])
            ->where('statut', '!=', 'annulee')
            ->sum('montant_total');

        $revenusMois = Commande::whereBetween('created_at', [$startOfMonth, Carbon::now()])
            ->where('statut', '!=', 'annulee')
            ->sum('montant_total');

        // Order counts
        $commandesAujourdhui = Commande::whereDate('created_at', $today)->count();
        $commandesEnAttente = Commande::where('statut', 'en_attente')->count();
        $commandesEnCours = Commande::whereIn('statut', ['confirmee', 'en_preparation', 'prete', 'en_livraison'])->count();
        $commandesLivreesAujourdhui = Commande::whereDate('created_at', $today)->where('statut', 'livree')->count();

        // Average order value
        $panierMoyen = Commande::where('statut', '!=', 'annulee')->avg('montant_total') ?? 0;

        // Reservations & Messages
        $reservationsAujourdhui = Reservation::whereDate('date_reservation', $today)->count();
        $messagesNonLus = Message::where('lu', false)->count();

        // Recent orders
        $recentOrders = Commande::with(['lignes', 'zoneLivraison', 'livreur'])
            ->latest()
            ->take(8)
            ->get();

        // Top 5 dishes
        $topPlats = DB::table('lignes_commande')
            ->select('nom_produit', DB::raw('SUM(quantite) as total_vendu'), DB::raw('SUM(sous_total_fcfa) as total_ca'))
            ->groupBy('nom_produit')
            ->orderByDesc('total_vendu')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'revenusAujourdhui',
            'revenusSemaine',
            'revenusMois',
            'commandesAujourdhui',
            'commandesEnAttente',
            'commandesEnCours',
            'commandesLivreesAujourdhui',
            'panierMoyen',
            'reservationsAujourdhui',
            'messagesNonLus',
            'recentOrders',
            'topPlats'
        ));
    }

    public function checkNewOrders(): JsonResponse
    {
        $enAttenteCount = Commande::where('statut', 'en_attente')->count();
        $latest = Commande::where('statut', 'en_attente')->latest()->first();

        return response()->json([
            'en_attente_count' => $enAttenteCount,
            'latest_id' => $latest?->id,
            'latest_code' => $latest?->code_commande,
            'latest_client' => $latest?->nom_client,
            'latest_total' => $latest ? $latest->montant_total_formate : null,
            'timestamp' => now()->timestamp,
        ]);
    }
}
