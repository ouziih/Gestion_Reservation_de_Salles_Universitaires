<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\SalleDtoBuilder;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\Renderer;

final class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
        private Renderer $renderer,
    ) {
    }

    public function index(): string
    {
        return $this->renderer->render('salle/index.php', [
            'salles' => $this->salles->all(),
        ]);
    }

    public function show(int $id): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->renderer->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        return $this->renderer->render('salle/show.php', ['salle' => $salle]);
    }

    public function create(): string
    {
        return $this->renderer->render('salle/form.php', [
            'salle' => null,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function store(array $data): string
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->renderer->render('salle/form.php', [
                'salle' => null,
                'errors' => $result->errors(),
                'data' => $data,
            ]);
        }

        $data = $result->data();
        $dto = (new SalleDtoBuilder())
            ->setNom($data['nom'])
            ->setBatiment($data['batiment'])
            ->setCapacite($data['capacite'])
            ->setType($data['type'])
            ->setActive($data['active'])
            ->build();

        $salle = $this->salles->create($dto);

        return $this->renderer->redirect('/salles/' . $salle->id);
    }

    public function edit(int $id): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->renderer->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        return $this->renderer->render('salle/form.php', [
            'salle' => $salle,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function update(int $id, array $data): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->renderer->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->renderer->render('salle/form.php', [
                'salle' => $salle,
                'errors' => $result->errors(),
                'data' => $data,
            ]);
        }

        $data = $result->data();
        $dto = (new SalleDtoBuilder())
            ->setNom($data['nom'])
            ->setBatiment($data['batiment'])
            ->setCapacite($data['capacite'])
            ->setType($data['type'])
            ->setActive($data['active'])
            ->build();

        $this->salles->update($id, $dto);

        return $this->renderer->redirect('/salles/' . $id);
    }
}