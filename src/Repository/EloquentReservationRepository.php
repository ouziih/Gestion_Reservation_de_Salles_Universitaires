<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\ReservationDto;
use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(
        private Reservation $model,
    ) {
    }

    public function findById(int $id): ?Reservation
    {
        return $this->model->newQuery()->find($id);
    }

    public function all(): Collection
    {
        return $this->model->newQuery()->get();
    }

    public function findBySalleId(int $salleId): Collection
    {
        return $this->model
            ->newQuery()
            ->where('salle_id', $salleId)
            ->get();
    }

    public function findConflicts(
        int $salleId,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin,
    ): Collection {
        return $this->model
            ->newQuery()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->get();
    }

    public function create(ReservationDto $data): Reservation
    {
        return $this->model->newQuery()->create([
            'salle_id' => $data->salleId,
            'responsable' => $data->responsable,
            'email' => $data->email,
            'motif' => $data->motif,
            'date_debut' => $data->dateDebut,
            'date_fin' => $data->dateFin,
            'statut' => $data->statut,
        ]);
    }

    public function cancel(int $id): bool
    {
        $reservation = $this->findById($id);

        if ($reservation === null) {
            return false;
        }

        $reservation->statut = 'annulée';

        return $reservation->save();
    }
}
