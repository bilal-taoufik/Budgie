<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TransactionFrequencyTest extends TestCase
{
    #[DataProvider('frequences')]
    public function test_les_frequences_produisent_les_bonnes_echeances(string $frequence, int $attendu): void
    {
        $transaction = new Transaction(['fractionnement' => $frequence, 'date_effet' => '2026-01-15', 'montant' => 10, 'type' => 'revenu']);
        $echeances = $transaction->nrbEcheance(Carbon::parse('2026-01-01'), Carbon::parse('2027-01-31'));

        $this->assertCount($attendu, $echeances);
    }

    public static function frequences(): array
    {
        return [['unique', 1], ['mensuel', 13], ['semestriel', 3], ['annuel', 2]];
    }

    public function test_la_date_de_fin_limite_les_echeances(): void
    {
        $transaction = new Transaction(['fractionnement' => 'mensuel', 'date_effet' => '2026-01-15', 'date_fin' => '2026-03-15', 'montant' => 10, 'type' => 'revenu']);

        $this->assertCount(3, $transaction->nrbEcheance(Carbon::parse('2026-01-01'), Carbon::parse('2026-12-31')));
    }
}