<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revenu extends Model
{
    // indique à Laravel ql table utiliser
    protected $table = 'revenues';
    // Champs autorisés à l'assignation en masse
    protected $fillable = [
        'account_id',
        'revenue_nom',
        'revenue_description',
        'revenue_montant',
        'revenue_fractionnement',
        'revenue_date_effet',
    ];

    // Un revenu appartient à un compte
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}