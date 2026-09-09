<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ReservationDto;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
    ) {
    }

    public function execute(ReservationDto $reservation): Reservation
    {
        $salle = $this->salles->findById($reservation->salleId);

        if ($salle === null || !$salle->active) {
            throw new SalleIndisponibleException('La salle demandée est inexistante ou inactive.');
        }

        $this->verifierDates($reservation->dateDebut, $reservation->dateFin);

        if (
            $this->reservations->findConflicts(
                $reservation->salleId,
                $reservation->dateDebut,
                $reservation->dateFin,
            )->isNotEmpty()
        ) {
            throw new SalleIndisponibleException('La salle est déjà réservée sur cette période.');
        }

        return $this->reservations->create($reservation);
    }

    private function verifierDates(DateTimeImmutable $debut, DateTimeImmutable $fin): void
    {
        if ($debut >= $fin) {
            throw new InvalidArgumentException('La date de fin doit être postérieure à la date de début.');
        }

        if ($debut <= new DateTimeImmutable()) {
            throw new InvalidArgumentException('La réservation doit commencer dans le futur.');
        }

        if ($fin->getTimestamp() - $debut->getTimestamp() > 4 * 60 * 60) {
            throw new InvalidArgumentException('La réservation ne peut pas durer plus de quatre heures.');
        }
    }
}
