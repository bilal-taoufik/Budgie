<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Depense extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'account_id',
        'montant',
        'fractionnement',
        'date_effet',
    ];

    // Relation avec le modèle Account
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}