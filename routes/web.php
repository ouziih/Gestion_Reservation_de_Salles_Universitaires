<?php

declare(strict_types=1);

use App\Controller\ReservationController;
use App\Controller\SalleController;

return [
    ['GET', '/', [SalleController::class, 'index']],
    ['GET', '/salles', [SalleController::class, 'index']],
    ['GET', '/salles/create', [SalleController::class, 'create']],
    ['POST', '/salles', [SalleController::class, 'store']],
    ['GET', '/salles/{id:\d+}', [SalleController::class, 'show']],
    ['GET', '/salles/{id:\d+}/edit', [SalleController::class, 'edit']],
    ['POST', '/salles/{id:\d+}/edit', [SalleController::class, 'update']],
    ['GET', '/reservations', [ReservationController::class, 'index']],
    ['GET', '/reservations/create', [ReservationController::class, 'create']],
    ['POST', '/reservations', [ReservationController::class, 'store']],
    ['GET', '/reservations/{id:\d+}', [ReservationController::class, 'show']],
    ['POST', '/reservations/{id:\d+}/cancel', [ReservationController::class, 'cancel']],
];