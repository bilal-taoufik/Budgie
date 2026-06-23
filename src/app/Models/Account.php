<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'solde',
        'interest_rate',
        'tax_rate',
    ];

    // Relation avec le modèle User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);  // clé étrangère user_id dans la table accounts
    }

    // Méthode pour calculer l'intérêt
    public function calculeInterest(): float
    {
        return $this->solde * ($this->interest_rate / 100);
    }

    // Méthode pour calculer l'imposition
    public function calculeTax(): float
    {
        return $this->solde * ($this->tax_rate / 100);
    }
}
