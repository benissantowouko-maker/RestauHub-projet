<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommande extends Model
{
    use HasFactory;

    protected $table = 'lignes_commande';

    protected $fillable = [
        'commande_id',
        'plat_id',
        'formule_id',
        'nom_produit',
        'quantite',
        'prix_unitaire_fcfa',
        'instructions_speciales',
        'sous_total_fcfa',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire_fcfa' => 'decimal:2',
        'sous_total_fcfa' => 'decimal:2',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plat::class);
    }

    public function formule(): BelongsTo
    {
        return $this->belongsTo(Formule::class);
    }

    public function getPrixUnitaireFormateAttribute(): string
    {
        return number_format((float) $this->prix_unitaire_fcfa, 0, ',', ' ') . ' FCFA';
    }

    public function getSousTotalFormateAttribute(): string
    {
        return number_format((float) $this->sous_total_fcfa, 0, ',', ' ') . ' FCFA';
    }
}
