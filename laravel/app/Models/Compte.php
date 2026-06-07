<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compte extends Model
{
    protected $table = 'compte';

    protected $primaryKey = 'cmp_id';

    protected $fillable = [
        'cmp_nom_appel',
        'cmp_description',
        'cmp_date_creation',
        'cmp_solde_initial',
        'cmp_taux_remuneration',
        'cmp_taux_imposition',
        'cmp_client_id',
    ];

    protected function casts(): array
    {
        return [
            'cmp_date_creation' => 'date',
            'cmp_solde_initial' => 'decimal:2',
            'cmp_taux_remuneration' => 'decimal:2',
            'cmp_taux_imposition' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cmp_client_id');
    }
}
