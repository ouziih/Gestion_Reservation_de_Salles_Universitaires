<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use DateTimeImmutable;

final class ReservationDansLeFuturRule implements ReservationDateRuleInterface
{
    public function verifier(DateTimeImmutable $debut, DateTimeImmutable $fin): ?array
    {
        if ($debut <= new DateTimeImmutable()) {
            return [
                'champ' => 'date_debut',
                'message' => 'La date de début doit être dans le futur.',
            ];
        }

        return null;
    }
}
