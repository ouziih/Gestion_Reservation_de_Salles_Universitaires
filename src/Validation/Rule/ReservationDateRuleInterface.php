<?php

declare(strict_types=1);

namespace App\Validation\Rule;

use DateTimeImmutable;

interface ReservationDateRuleInterface
{
    public function verifier(DateTimeImmutable $debut, DateTimeImmutable $fin): ?array;
}
