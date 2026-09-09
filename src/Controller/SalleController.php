<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\SalleDtoBuilder;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;

final class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
    ) {
    }

    public function index(): string
    {
        return $this->render('salle/index.php', [
            'salles' => $this->salles->all(),
        ]);
    }

    public function show(int $id): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        return $this->render('salle/show.php', ['salle' => $salle]);
    }

    public function create(): string
    {
        return $this->render('salle/form.php', [
            'salle' => null,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function store(array $data): string
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->render('salle/form.php', [
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

        return $this->redirect('/salles/' . $salle->id);
    }

    public function edit(int $id): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        return $this->render('salle/form.php', [
            'salle' => $salle,
            'errors' => [],
            'data' => [],
        ]);
    }

    public function update(int $id, array $data): string
    {
        $salle = $this->salles->findById($id);

        if ($salle === null) {
            return $this->render('error/404.php', ['message' => 'Salle introuvable.'], 404);
        }

        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->render('salle/form.php', [
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

        return $this->redirect('/salles/' . $id);
    }

    private function render(string $template, array $data, int $status = 200): string
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);
        ob_start();
        require dirname(__DIR__, 2) . '/templates/' . $template;
        $content = (string) ob_get_clean();

        ob_start();
        require dirname(__DIR__, 2) . '/templates/layout/base.php';

        return (string) ob_get_clean();
    }

    private function redirect(string $location): string
    {
        header('Location: ' . $location, true, 303);

        return '';
    }
}
