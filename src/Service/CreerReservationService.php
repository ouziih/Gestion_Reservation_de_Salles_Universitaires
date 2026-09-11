<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ReservationDto;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Validation\Rule\DateDebutAvantDateFinRule;
use App\Validation\Rule\DureeMaximaleRule;
use App\Validation\Rule\ReservationDansLeFuturRule;
use App\Validation\Rule\ReservationDateRuleInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class CreerReservationService
{
    /** @var ReservationDateRuleInterface[] */
    private readonly array $reglesDate;

    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
        ?array $reglesDate = null,
    ) {
        $this->reglesDate = $reglesDate ?? [
            new DateDebutAvantDateFinRule(),
            new DureeMaximaleRule(),
            new ReservationDansLeFuturRule(),
        ];
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
        foreach ($this->reglesDate as $regle) {
            $erreur = $regle->verifier($debut, $fin);

            if ($erreur !== null) {
                throw new InvalidArgumentException($erreur['message']);
            }
        }
    }
}