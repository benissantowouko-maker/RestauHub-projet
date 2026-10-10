<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plat extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'nom',
        'slug',
        'description',
        'prix_fcfa',
        'photo_url',
        'disponible',
        'est_populaire',
        'temps_preparation_minutes',
        'ordre_affichage',
    ];

    protected function casts(): array
    {
        return [
            'prix_fcfa' => 'decimal:2',
            'disponible' => 'boolean',
            'est_populaire' => 'boolean',
            'temps_preparation_minutes' => 'integer',
            'ordre_affichage' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function scopeDisponible(Builder $query): Builder
    {
        return $query->where('disponible', true);
    }

    public function scopePopulaire(Builder $query): Builder
    {
        return $query->where('est_populaire', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordre_affichage', 'asc')->orderBy('nom', 'asc');
    }

    public function getPrixFormateAttribute(): string
    {
        return number_format((float) $this->prix_fcfa, 0, ',', ' ') . ' FCFA';
    }
}
