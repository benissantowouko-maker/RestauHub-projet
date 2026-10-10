<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZoneLivraison extends Model
{
    use HasFactory;

    protected $table = 'zones_livraison';

    protected $fillable = [
        'nom_zone',
        'frais_fcfa',
        'delai_estime_min',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'frais_fcfa' => 'decimal:2',
            'delai_estime_min' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'zone_livraison_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function getFraisFormateAttribute(): string
    {
        return number_format((float) $this->frais_fcfa, 0, ',', ' ') . ' FCFA';
    }
}
