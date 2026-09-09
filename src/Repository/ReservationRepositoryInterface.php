<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\ReservationDto;
use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation;

    public function all(): Collection;

    public function findBySalleId(int $salleId): Collection;

    public function findConflicts(
        int $salleId,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin,
    ): Collection;

    public function create(ReservationDto $data): Reservation;

    public function cancel(int $id): bool;
}
