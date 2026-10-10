<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_commande',
        'tracking_token',
        'client_id',
        'user_id',
        'nom_client',
        'telephone_client',
        'email_client',
        'type',
        'statut',
        'zone_livraison_id',
        'adresse_livraison',
        'quartier',
        'latitude',
        'longitude',
        'note_livreur',
        'mode_paiement',
        'statut_paiement',
        'reference_paiement',
        'sous_total_fcfa',
        'frais_livraison_fcfa',
        'montant_total',
        'livreur_id',
        'date_assignation_livreur',
        'date_recuperation_livreur',
        'date_livraison',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'sous_total_fcfa' => 'decimal:2',
            'frais_livraison_fcfa' => 'decimal:2',
            'montant_total' => 'decimal:2',
            'date_assignation_livreur' => 'datetime',
            'date_recuperation_livreur' => 'datetime',
            'date_livraison' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function zoneLivraison(): BelongsTo
    {
        return $this->belongsTo(ZoneLivraison::class, 'zone_livraison_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'commande_id');
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class, 'commande_id');
    }

    public function scopeAujourdhui(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeCetteSemaine(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function scopeCeMois(Builder $query): Builder
    {
        return $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopePrete(Builder $query): Builder
    {
        return $query->where('statut', 'prete');
    }

    public function getMontantTotalFormateAttribute(): string
    {
        return number_format((float) $this->montant_total, 0, ',', ' ') . ' FCFA';
    }

    public function getSousTotalFormateAttribute(): string
    {
        return number_format((float) $this->sous_total_fcfa, 0, ',', ' ') . ' FCFA';
    }

    public function getFraisLivraisonFormateAttribute(): string
    {
        return number_format((float) $this->frais_livraison_fcfa, 0, ',', ' ') . ' FCFA';
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'en_livraison' => 'En livraison',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getStatutBadgeClassAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'bg-amber-100 text-amber-800 border-amber-300',
            'confirmee' => 'bg-blue-100 text-blue-800 border-blue-300',
            'en_preparation' => 'bg-orange-100 text-orange-800 border-orange-300',
            'prete' => 'bg-purple-100 text-purple-800 border-purple-300',
            'en_livraison' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'livree' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'annulee' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getModePaiementLabelAttribute(): string
    {
        return match ($this->mode_paiement) {
            'especes_livraison' => 'Espèces à la livraison',
            't_money' => 'T-Money (Togocom)',
            'flooz' => 'Flooz (Moov Africa)',
            'carte' => 'Carte Bancaire',
            default => $this->mode_paiement,
        };
    }

    /**
     * Step index for tracking progress:
     * 1 = En attente, 2 = Confirmée, 3 = En préparation, 4 = Prête, 5 = En livraison, 6 = Livrée
     */
    public function getStepProgressAttribute(): int
    {
        return match ($this->statut) {
            'en_attente' => 1,
            'confirmee' => 2,
            'en_preparation' => 3,
            'prete' => 4,
            'en_livraison' => 5,
            'livree' => 6,
            'annulee' => 0,
            default => 1,
        };
    }
}
