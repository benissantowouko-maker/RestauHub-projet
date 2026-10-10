<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // CA stats
        $caAujourdhui = Commande::whereDate('created_at', $today)->where('statut', '!=', 'annulee')->sum('montant_total');
        $caSemaine = Commande::whereBetween('created_at', [$startOfWeek, Carbon::now()])->where('statut', '!=', 'annulee')->sum('montant_total');
        $caMois = Commande::whereBetween('created_at', [$startOfMonth, Carbon::now()])->where('statut', '!=', 'annulee')->sum('montant_total');
        $caTotal = Commande::where('statut', '!=', 'annulee')->sum('montant_total');

        // Order count & Panier Moyen
        $totalCommandes = Commande::where('statut', '!=', 'annulee')->count();
        $panierMoyen = $totalCommandes > 0 ? ($caTotal / $totalCommandes) : 0;

        // Daily trend (last 7 days)
        $ventes7Jours = Commande::where('statut', '!=', 'annulee')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(montant_total) as ca'), DB::raw('COUNT(*) as total_commandes'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top 10 best-selling dishes
        $topPlats = DB::table('lignes_commande')
            ->select('nom_produit', DB::raw('SUM(quantite) as total_vendu'), DB::raw('SUM(sous_total_fcfa) as total_ca'))
            ->groupBy('nom_produit')
            ->orderByDesc('total_vendu')
            ->take(10)
            ->get();

        // Payment mode breakdown
        $parModePaiement = Commande::where('statut', '!=', 'annulee')
            ->select('mode_paiement', DB::raw('COUNT(*) as count'), DB::raw('SUM(montant_total) as total'))
            ->groupBy('mode_paiement')
            ->get();

        return view('admin.revenus.index', compact(
            'caAujourdhui',
            'caSemaine',
            'caMois',
            'caTotal',
            'panierMoyen',
            'totalCommandes',
            'ventes7Jours',
            'topPlats',
            'parModePaiement'
        ));
    }
}
