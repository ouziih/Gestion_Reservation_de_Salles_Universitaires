<?php

declare(strict_types=1);

namespace App\DTO;

final class SalleDtoBuilder
{
    private ?string $nom = null;
    private ?string $batiment = null;
    private ?int $capacite = null;
    private ?string $type = null;
    private ?bool $active = null;

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function setBatiment(string $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function build(): SalleDto
    {
        if (
            $this->nom === null
            || $this->batiment === null
            || $this->capacite === null
            || $this->type === null
            || $this->active === null
        ) {
            throw new \InvalidArgumentException('Tous les champs de la salle sont obligatoires.');
        }

        return new SalleDto(
            nom: $this->nom,
            batiment: $this->batiment,
            capacite: $this->capacite,
            type: $this->type,
            active: $this->active,
        );
    }
}
