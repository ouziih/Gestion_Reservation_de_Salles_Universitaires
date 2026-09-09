<?php

declare(strict_types=1);
?>
<h1>Liste des réservations</h1>
<p><a href="/reservations/create">Créer une réservation</a></p>
<?php if ($reservations->isEmpty()): ?>
    <p>Aucune réservation.</p>
<?php else: ?>
    <ul>
        <?php foreach ($reservations as $reservation): ?>
            <li>
                <a href="/reservations/<?= (int) $reservation->id ?>">
                    <?= htmlspecialchars($reservation->responsable, ENT_QUOTES, 'UTF-8') ?>
                </a>
                - <?= htmlspecialchars($reservation->statut, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
