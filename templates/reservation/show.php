<?php

declare(strict_types=1);
?>
<h1>Réservation #<?= (int) $reservation->id ?></h1>
<dl>
    <dt>Responsable</dt>
    <dd><?= htmlspecialchars($reservation->responsable, ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>E-mail</dt>
    <dd><?= htmlspecialchars($reservation->email, ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>Motif</dt>
    <dd><?= htmlspecialchars($reservation->motif, ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>Début</dt>
    <dd><?= htmlspecialchars($reservation->date_debut->format('Y-m-d H:i'), ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>Fin</dt>
    <dd><?= htmlspecialchars($reservation->date_fin->format('Y-m-d H:i'), ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>Statut</dt>
    <dd><?= htmlspecialchars($reservation->statut, ENT_QUOTES, 'UTF-8') ?></dd>
</dl>
<?php if ($reservation->statut !== 'annulée'): ?>
    <form method="post" action="/reservations/<?= (int) $reservation->id ?>/cancel">
        <button type="submit">Annuler</button>
    </form>
<?php endif; ?>
<p><a href="/reservations">Retour aux réservations</a></p>
