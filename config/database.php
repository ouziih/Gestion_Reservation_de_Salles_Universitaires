<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required([
    'DB_DRIVER',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
])->notEmpty();

$configuration = [
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
];

$capsule = new Capsule();
$capsule->addConnection($configuration);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $capsule->getConnection()->getPdo();
} catch (\PDOException $exception) {
    throw new RuntimeException(
        'Impossible de se connecter à la base de données.',
        0,
        $exception
    );
}

return $capsule;