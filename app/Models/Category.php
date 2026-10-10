<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
        'photo_url',
        'ordre_affichage',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'ordre_affichage' => 'integer',
        ];
    }

    public function plats(): HasMany
    {
        return $this->hasMany(Plat::class, 'categorie_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordre_affichage', 'asc')->orderBy('nom', 'asc');
    }
}
