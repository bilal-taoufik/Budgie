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
        'last_debited_at',
    ];

    // Relation avec le modèle Account
    protected function casts(): array
    {
        return [
            'date_effet' => 'date',
            'last_debited_at' => 'date',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}