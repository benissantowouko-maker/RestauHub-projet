<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formule extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'composants',
        'prix_fcfa',
        'photo_url',
        'disponible',
    ];

    protected function casts(): array
    {
        return [
            'prix_fcfa' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function scopeDisponible(Builder $query): Builder
    {
        return $query->where('disponible', true);
    }

    public function getPrixFormateAttribute(): string
    {
        return number_format((float) $this->prix_fcfa, 0, ',', ' ') . ' FCFA';
    }
}
