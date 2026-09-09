<?php

declare(strict_types=1);

$title = $title ?? 'Réservation de salles';
$content = $content ?? '';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
<main>
    <?= $content ?>
</main>
</body>
</html>
