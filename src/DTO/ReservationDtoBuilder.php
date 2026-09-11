<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;
use InvalidArgumentException;

final class ReservationDtoBuilder
{
    private ?int $salleId = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;
    private ?string $statut = null;

    public function setSalleId(int $salleId): self
    {
        $this->salleId = $salleId;

        return $this;
    }

    public function setResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function setDateDebut(DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function setDateFin(DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function build(): ReservationDto
    {
        if (
            $this->salleId === null
            || $this->responsable === null
            || $this->email === null
            || $this->motif === null
            || $this->dateDebut === null
            || $this->dateFin === null
            || $this->statut === null
        ) {
            throw new InvalidArgumentException('Tous les champs de la réservation sont obligatoires.');
        }

        return new ReservationDto(
            salleId: $this->salleId,
            responsable: $this->responsable,
            email: $this->email,
            motif: $this->motif,
            dateDebut: $this->dateDebut,
            dateFin: $this->dateFin,
            statut: $this->statut,
        );
    }
}
