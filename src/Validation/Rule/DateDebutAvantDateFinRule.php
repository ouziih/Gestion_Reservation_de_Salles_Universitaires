<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use DateTimeImmutable;

final class DateDebutAvantDateFinRule implements ReservationDateRuleInterface
{
    public function verifier(DateTimeImmutable $debut, DateTimeImmutable $fin): ?array
    {
        if ($debut >= $fin) {
            return [
                'champ' => 'date_fin',
                'message' => 'La date de fin doit être postérieure à la date de début.',
            ];
        }

        return null;
    }
}
