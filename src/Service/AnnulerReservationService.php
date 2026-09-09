<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use RuntimeException;

final class AnnulerReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservations,
    ) {
    }

    public function execute(int $id): Reservation
    {
        if ($this->reservations->findById($id) === null) {
            throw new ReservationIntrouvableException($id);
        }

        if (!$this->reservations->cancel($id)) {
            throw new RuntimeException('La réservation ' . $id . ' n’a pas pu être annulée.');
        }

        $reservation = $this->reservations->findById($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException($id);
        }

        return $reservation;
    }
}
