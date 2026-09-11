<?php

declare(strict_types=1);


use Illuminate\Database\Schema\Blueprint;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$capsule = require dirname(__DIR__, 2) . '/config/database.php';

if (!$capsule->schema()->hasTable('salles')) {
    $capsule->schema()->create('salles', function (Blueprint $table): void {
        $table->id();
        $table->string('nom', 100);
        $table->string('batiment', 100);
        $table->unsignedInteger('capacite');
        $table->string('type', 30);
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
}