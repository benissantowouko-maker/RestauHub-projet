<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory;

    protected $fillable = [
        'cle',
        'valeur',
        'groupe',
        'description',
    ];

    public static function get(string $cle, ?string $default = null): ?string
    {
        $param = static::where('cle', $cle)->first();
        return $param ? $param->valeur : $default;
    }

    public static function set(string $cle, ?string $valeur, string $groupe = 'general', ?string $description = null): static
    {
        return static::updateOrCreate(
            ['cle' => $cle],
            [
                'valeur' => $valeur,
                'groupe' => $groupe,
                'description' => $description,
            ]
        );
    }
}
