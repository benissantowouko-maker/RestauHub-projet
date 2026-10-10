<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Formule;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\ZoneLivraison;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $zones = ZoneLivraison::active()->get();
        return view('pages.checkout', compact('zones'));
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'nom_client' => 'required|string|max:120',
            'telephone_client' => 'required|string|max:30',
            'email_client' => 'nullable|email|max:150',
            'type' => 'required|in:livraison,a_emporter,sur_place',
            'zone_livraison_id' => 'nullable|exists:zones_livraison,id',
            'adresse_livraison' => 'required_if:type,livraison|nullable|string|max:255',
            'quartier' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'note_livreur' => 'nullable|string|max:500',
            'mode_paiement' => 'required|in:especes_livraison,t_money,flooz,carte',
            'numero_mobile_money' => 'nullable|string|max:30',
            'panier_data' => 'required|string', // JSON string of items: [{id, type, qty, note}]
        ]);

        $panierItems = json_decode($request->panier_data, true);
        if (! is_array($panierItems) || empty($panierItems)) {
            return back()->with('error', 'Votre panier est vide. Veuillez sélectionner des plats avant de commander.')->withInput();
        }

        try {
            $commande = DB::transaction(function () use ($request, $panierItems) {
                // Find or create Client
                $client = null;
                if ($request->filled('telephone_client')) {
                    $client = Client::firstOrCreate(
                        ['telephone' => $request->telephone_client],
                        [
                            'user_id' => Auth::id(),
                            'nom' => $request->nom_client,
                            'email' => $request->email_client,
                            'adresse_par_defaut' => $request->adresse_livraison,
                            'compte_utilisateur' => Auth::check(),
                        ]
                    );
                }

                // Calculate Totals securely from DB
                $sousTotal = 0;
                $lignesToInsert = [];

                foreach ($panierItems as $item) {
                    $type = $item['type'] ?? 'plat';
                    $id = (int) ($item['id'] ?? 0);
                    $qty = max(1, (int) ($item['qty'] ?? 1));
                    $note = $item['note'] ?? null;

                    if ($type === 'formule') {
                        $formule = Formule::find($id);
                        if ($formule) {
                            $itemTotal = $formule->prix_fcfa * $qty;
                            $sousTotal += $itemTotal;
                            $lignesToInsert[] = [
                                'formule_id' => $formule->id,
                                'plat_id' => null,
                                'nom_produit' => $formule->nom,
                                'quantite' => $qty,
                                'prix_unitaire_fcfa' => $formule->prix_fcfa,
                                'instructions_speciales' => $note,
                                'sous_total_fcfa' => $itemTotal,
                            ];
                        }
                    } else {
                        $plat = Plat::find($id);
                        if ($plat) {
                            $itemTotal = $plat->prix_fcfa * $qty;
                            $sousTotal += $itemTotal;
                            $lignesToInsert[] = [
                                'plat_id' => $plat->id,
                                'formule_id' => null,
                                'nom_produit' => $plat->nom,
                                'quantite' => $qty,
                                'prix_unitaire_fcfa' => $plat->prix_fcfa,
                                'instructions_speciales' => $note,
                                'sous_total_fcfa' => $itemTotal,
                            ];
                        }
                    }
                }

                if (empty($lignesToInsert)) {
                    throw new \Exception('Aucun article valide trouvé dans votre panier.');
                }

                // Shipping fee
                $fraisLivraison = 0;
                if ($request->type === 'livraison') {
                    if ($request->zone_livraison_id) {
                        $zone = ZoneLivraison::find($request->zone_livraison_id);
                        $fraisLivraison = $zone ? (float) $zone->frais_fcfa : 1000;
                    } else {
                        $fraisLivraison = 1000;
                    }
                }

                $montantTotal = $sousTotal + $fraisLivraison;

                // Generate unique order number CP-2026-XXXX
                $lastOrder = Commande::max('id') ?? 0;
                $codeCommande = 'CP-2026-' . str_pad((string) ($lastOrder + 1), 4, '0', STR_PAD_LEFT);
                $trackingToken = Str::random(32);

                // Payment details
                $modePaiement = $request->mode_paiement;
                $statutPaiement = 'a_la_livraison';
                $referencePaiement = null;

                if (in_array($modePaiement, ['t_money', 'flooz'], true)) {
                    $prefix = $modePaiement === 't_money' ? 'TMONEY-TG-' : 'FLOOZ-MOOV-';
                    $referencePaiement = $prefix . strtoupper(Str::random(8));
                    $statutPaiement = 'paye';
                }

                // Create Order
                $commande = Commande::create([
                    'code_commande' => $codeCommande,
                    'tracking_token' => $trackingToken,
                    'client_id' => $client?->id,
                    'user_id' => Auth::id(),
                    'nom_client' => $request->nom_client,
                    'telephone_client' => $request->telephone_client,
                    'email_client' => $request->email_client,
                    'type' => $request->type,
                    'statut' => 'en_attente',
                    'zone_livraison_id' => $request->zone_livraison_id,
                    'adresse_livraison' => $request->adresse_livraison,
                    'quartier' => $request->quartier,
                    'latitude' => $request->latitude ? (float) $request->latitude : 6.1375,
                    'longitude' => $request->longitude ? (float) $request->longitude : 1.2123,
                    'note_livreur' => $request->note_livreur,
                    'mode_paiement' => $modePaiement,
                    'statut_paiement' => $statutPaiement,
                    'reference_paiement' => $referencePaiement,
                    'sous_total_fcfa' => $sousTotal,
                    'frais_livraison_fcfa' => $fraisLivraison,
                    'montant_total' => $montantTotal,
                ]);

                // Create order lines
                foreach ($lignesToInsert as $ligne) {
                    $commande->lignes()->create($ligne);
                }

                // Create invoice RAF-2026-XXXX
                $lastInvoice = Facture::max('id') ?? 0;
                $numeroFacture = 'RAF-2026-' . str_pad((string) ($lastInvoice + 1), 4, '0', STR_PAD_LEFT);

                Facture::create([
                    'numero_facture' => $numeroFacture,
                    'commande_id' => $commande->id,
                    'montant_fcfa' => $montantTotal,
                    'statut_envoi' => 'envoyee',
                    'date_emission' => Carbon::now(),
                ]);

                return $commande;
            });

            return redirect()->route('suivi.commande', $commande->tracking_token)
                ->with('success', "Votre commande #{$commande->code_commande} a bien été enregistrée ! Vous pouvez suivre son statut en temps réel ci-dessous.");

        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue lors de la création de la commande : ' . $e->getMessage())->withInput();
        }
    }
}
