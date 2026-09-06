<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$capsule = require dirname(__DIR__, 2) . '/config/database.php';

if (!$capsule->schema()->hasTable('reservations')) {
    $capsule->schema()->create('reservations', function (Blueprint $table): void {
        $table->id();
        $table->foreignId('salle_id')->constrained('salles');
        $table->string('responsable', 120);
        $table->string('email', 255);
        $table->string('motif', 255);
        $table->dateTime('date_debut');
        $table->dateTime('date_fin');
        $table->string('statut', 20)->default('confirmee');
        $table->timestamps();
    });
}
