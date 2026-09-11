<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\SalleDto;
use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

final class EloquentSalleRepository implements SalleRepositoryInterface
{
    public function __construct(
        private Salle $model,
    ) {
    }

    public function findById(int $id): ?Salle
    {
        return $this->model->newQuery()->find($id);
    }

    public function findActiveById(int $id): ?Salle
    {
        return $this->model
            ->newQuery()
            ->whereKey($id)
            ->where('active', true)
            ->first();
    }

    public function all(): Collection
    {
        return $this->model->newQuery()->get();
    }

    public function create(SalleDto $data): Salle
    {
        return $this->model->newQuery()->create([
            'nom' => $data->nom,
            'batiment' => $data->batiment,
            'capacite' => $data->capacite,
            'type' => $data->type,
            'active' => $data->active,
        ]);
    }

    public function update(int $id, SalleDto $data): Salle
    {
        $salle = $this->model->newQuery()->find($id);

        if ($salle === null) {
            throw new RuntimeException('La salle ' . $id . ' est introuvable.');
        }

        $salle->fill([
            'nom' => $data->nom,
            'batiment' => $data->batiment,
            'capacite' => $data->capacite,
            'type' => $data->type,
            'active' => $data->active,
        ]);

        $salle->save();

        return $salle;
    }
}