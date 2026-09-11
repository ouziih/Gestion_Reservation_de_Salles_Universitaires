<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use DateTimeImmutable;

final class DureeMaximaleRule implements ReservationDateRuleInterface
{
    public function __construct(
        private readonly int $dureeMaxEnSecondes = 4 * 60 * 60,
    ) {
    }

    public function verifier(DateTimeImmutable $debut, DateTimeImmutable $fin): ?array
    {
        if ($fin->getTimestamp() - $debut->getTimestamp() > $this->dureeMaxEnSecondes) {
            return [
                'champ' => 'date_fin',
                'message' => 'La réservation ne peut pas durer plus de quatre heures.',
            ];
        }

        return null;
    }
}
