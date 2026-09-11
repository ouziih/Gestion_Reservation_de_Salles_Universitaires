<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;



$requiredVariables = [
    'DB_DRIVER',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
];

$configuration = [];

foreach ($requiredVariables as $variable) {
    $value = getenv($variable);
    if ($value === false || $value === '') {
        throw new RuntimeException(sprintf('La variable d’environnement %s est absente.', $variable));
    }
    $configuration[$variable] = $value;
}





$password = getenv('DB_PASSWORD');



$connection = [
    'driver' => $configuration['DB_DRIVER'],
    'host' => $configuration['DB_HOST'],
    'port' => (int) $configuration['DB_PORT'],
    'database' => $configuration['DB_DATABASE'],
    'username' => $configuration['DB_USERNAME'],
    'password' => $password === false ? '' : $password,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
];

$capsule = new Capsule();
$capsule->addConnection($connection);
$capsule->setAsGlobal();
$capsule->bootEloquent();
return $capsule;
