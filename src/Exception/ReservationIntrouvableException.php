<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

final class ReservationIntrouvableException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct('La réservation ' . $id . ' est introuvable.');
    }
}
