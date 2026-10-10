<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_reservation',
        'nom',
        'telephone',
        'email',
        'nombre_personnes',
        'date_reservation',
        'heure_reservation',
        'statut',
        'occasion',
        'remarques',
        'table_numero',
    ];

    protected function casts(): array
    {
        return [
            'nombre_personnes' => 'integer',
            'date_reservation' => 'date',
        ];
    }

    public function scopeAujourdhui(Builder $query): Builder
    {
        return $query->whereDate('date_reservation', Carbon::today());
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'annulee' => 'Annulée',
            'terminee' => 'Terminée',
            default => ucfirst($this->statut),
        };
    }

    public function getStatutBadgeClassAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'bg-amber-100 text-amber-800 border-amber-300',
            'confirmee' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'annulee' => 'bg-rose-100 text-rose-800 border-rose-300',
            'terminee' => 'bg-slate-100 text-slate-800 border-slate-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
