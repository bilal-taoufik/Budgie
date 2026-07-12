<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'nom',
        'description',
        'montant',
        'type',
        'fractionnement',
        'date_effet',
        'date_fin',
        'derniere_application',
    ];

    protected $casts = [
        'date_effet' => 'datetime',
        'date_fin' => 'datetime',
        'derniere_application' => 'datetime',
        'montant' => 'float',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // methode qui recupere le montant choisis et le signe pour ajouter ou soustraire selon le type
    public function montantSelonType()
    {
        $montant = $this->montant;

        if ($this->type === 'depense') {
            return -$montant;
        }

        return $montant;
    }

    // methode pour avoir les dates d'echeance entre la date d'effet et aujoud'hui
    public function nrbEcheance(Carbon $debut, Carbon $fin): Collection
    {
        $debut = $debut->copy()->startOfDay(); // date de debut recus on le met a minuit
        $fin = $fin->copy()->startOfDay(); // pareil ici date de de fin recus on le met a minuit
        $dateEffet = $this->date_effet->copy()->startOfDay(); // recupere la date d'effet de la transaction
        $dateFin = $this->date_fin?->copy()->startOfDay(); // recupere la date si null passer

        if ($dateFin && $dateFin->lt($fin)) {
            $fin = $dateFin;
        }

        // cas absurdes : bornes inversees, ou periode demandee entierement
        // avant meme le debut de la transaction -> rien a retourner
        if ($fin->lt($debut) || $fin->lt($dateEffet)) {
            return collect();
        }

        // transaction non repetee : une seule occurrence possible, date_effet elle-meme
        if ($this->fractionnement === 'unique') {
            if ($dateEffet->betweenIncluded($debut, $fin)) {
                return collect([$dateEffet]);
            }

            return collect();
        }

        // on determine tous les combien de mois la transaction se repete
        if ($this->fractionnement === 'mensuel') {
            $intervalle = 1;
        } elseif ($this->fractionnement === 'semestriel') {
            $intervalle = 6;
        } elseif ($this->fractionnement === 'annuel') {
            $intervalle = 12;
        } else {
            // valeur de fractionnement inconnue/invalide -> aucune occurrence
            return collect();
        }

        // le jour du mois ou tombe la transaction (ex: 15 pour "tous les 15 du mois")
        $jourEffet = $dateEffet->day;

        // on se place au debut du mois de depart, et on va avancer mois par mois
        $mois = $dateEffet->copy()->startOfMonth();
        $occurrences = collect();

        // on parcourt les mois un par un, tant qu'on n'a pas depasse la fin
        while ($mois->lte($fin)) {
            // on calcule la date d'occurrence pour ce mois :
            // min(...) evite un jour invalide (ex: le 31 fevrier n'existe pas,
            // on retombe alors sur le dernier jour du mois)
            $occurrence = $mois->copy()->day(min($jourEffet, $mois->daysInMonth));

            // on ne garde l'occurrence que si elle est apres le debut de la transaction
            // ET qu'elle tombe bien dans l'intervalle demande [$debut, $fin]
            if ($occurrence->gte($dateEffet) && $occurrence->betweenIncluded($debut, $fin)) {
                $occurrences->push($occurrence);
            }

            // on avance au prochain mois concerne (ex: +1, +6 ou +12 mois)
            // "NoOverflow" evite le bug ou "31 janvier + 1 mois" saute a mars
            // au lieu de rester en fevrier
            $mois->addMonthsNoOverflow($intervalle);
        }

        return $occurrences;
    }

    // methode pour calculer le nombre d'echeances entre $debut et $fin, 
    // multiplie par le montant (avec son signe selon depense/revenu)
    public function montantTotal(Carbon $debut, Carbon $fin)
    {
        return $this->nrbEcheance($debut, $fin)->count() * $this->montantSelonType();
    }
}