<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_facture',
        'commande_id',
        'montant_fcfa',
        'statut_envoi',
        'fichier_pdf_url',
        'date_emission',
    ];

    protected $casts = [
        'montant_fcfa' => 'decimal:2',
        'date_emission' => 'datetime',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function scopeAujourdhui(Builder $query): Builder
    {
        return $query->whereDate('date_emission', Carbon::today());
    }

    public function scopeDerniers7Jours(Builder $query): Builder
    {
        return $query->where('date_emission', '>=', Carbon::now()->subDays(7));
    }

    public function scopeCeMois(Builder $query): Builder
    {
        return $query->whereMonth('date_emission', Carbon::now()->month)->whereYear('date_emission', Carbon::now()->year);
    }

    public function getMontantFormateAttribute(): string
    {
        return number_format((float) $this->montant_fcfa, 0, ',', ' ') . ' FCFA';
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut_envoi) {
            'envoyee' => 'Envoyée',
            'en_attente' => 'En attente',
            'echec' => 'Échec envoi',
            default => ucfirst($this->statut_envoi),
        };
    }

    public function getStatutBadgeClassAttribute(): string
    {
        return match ($this->statut_envoi) {
            'envoyee' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'en_attente' => 'bg-amber-100 text-amber-800 border-amber-300',
            'echec' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
