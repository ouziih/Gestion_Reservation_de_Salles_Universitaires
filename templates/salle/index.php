<?php

declare(strict_types=1);
?>
<h1>Liste des salles</h1>
<p><a href="/salles/create">Ajouter une salle</a></p>
<?php if ($salles->isEmpty()): ?>
    <p>Aucune salle disponible.</p>
<?php else: ?>
    <ul>
        <?php foreach ($salles as $salle): ?>
            <li>
                <a href="/salles/<?= (int) $salle->id ?>">
                    <?= htmlspecialchars($salle->nom, ENT_QUOTES, 'UTF-8') ?>
                </a>
                - <?= htmlspecialchars($salle->batiment, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
