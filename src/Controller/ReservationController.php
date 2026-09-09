<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ReservationDtoBuilder;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use DateTimeImmutable;
use InvalidArgumentException;

final class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservations,
        private SalleRepositoryInterface $salles,
        private ReservationValidator $validator,
        private CreerReservationService $createService,
        private AnnulerReservationService $cancelService,
    ) {
    }

    public function index(): string
    {
        return $this->render('reservation/index.php', [
            'reservations' => $this->reservations->all(),
        ]);
    }

    public function show(int $id): string
    {
        $reservation = $this->reservations->findById($id);

        if ($reservation === null) {
            return $this->render('error/404.php', ['message' => 'Réservation introuvable.'], 404);
        }

        return $this->render('reservation/show.php', ['reservation' => $reservation]);
    }

    public function create(): string
    {
        return $this->render('reservation/form.php', [
            'salles' => $this->salles->all(),
            'errors' => [],
            'data' => [],
        ]);
    }

    public function store(array $data): string
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->render('reservation/form.php', [
                'salles' => $this->salles->all(),
                'errors' => $result->errors(),
                'data' => $data,
            ]);
        }

        try {
            $accepted = $result->data();
            $dto = (new ReservationDtoBuilder())
                ->setSalleId($accepted['salle_id'])
                ->setResponsable($accepted['responsable'])
                ->setEmail($accepted['email'])
                ->setMotif($accepted['motif'])
                ->setDateDebut($accepted['date_debut'])
                ->setDateFin($accepted['date_fin'])
                ->setStatut($accepted['statut'])
                ->build();

            $reservation = $this->createService->execute($dto);
        } catch (SalleIndisponibleException|InvalidArgumentException $exception) {
            return $this->render('reservation/form.php', [
                'salles' => $this->salles->all(),
                'errors' => ['general' => [$exception->getMessage()]],
                'data' => $data,
            ]);
        }

        return $this->redirect('/reservations/' . $reservation->id);
    }

    public function cancel(int $id): string
    {
        try {
            $this->cancelService->execute($id);
        } catch (ReservationIntrouvableException $exception) {
            return $this->render('error/404.php', [
                'message' => $exception->getMessage(),
            ], 404);
        }

        return $this->redirect('/reservations/' . $id);
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
