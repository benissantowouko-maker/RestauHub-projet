<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'sujet',
        'message',
        'lu',
        'repondu',
        'notes_admin',
    ];

    protected function casts(): array
    {
        return [
            'lu' => 'boolean',
            'repondu' => 'boolean',
        ];
    }

    public function scopeNonLu(Builder $query): Builder
    {
        return $query->where('lu', false);
    }
}
