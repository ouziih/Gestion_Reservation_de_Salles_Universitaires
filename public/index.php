<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;

header('Content-Type: text/plain; charset=UTF-8');

(new Application())->hello();
