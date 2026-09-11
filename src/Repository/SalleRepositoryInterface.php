<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\SalleDto;
use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;

interface SalleRepositoryInterface
{
    public function findById(int $id): ?Salle;

    public function findActiveById(int $id): ?Salle;

    public function all(): Collection;

    public function create(SalleDto $data): Salle;

    public function update(int $id, SalleDto $data): Salle;
}