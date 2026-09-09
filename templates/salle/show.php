<?php

declare(strict_types=1);
?>
<h1><?= htmlspecialchars($salle->nom, ENT_QUOTES, 'UTF-8') ?></h1>
<dl>
    <dt>Bâtiment</dt>
    <dd><?= htmlspecialchars($salle->batiment, ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>Capacité</dt>
    <dd><?= (int) $salle->capacite ?></dd>
    <dt>Type</dt>
    <dd><?= htmlspecialchars($salle->type, ENT_QUOTES, 'UTF-8') ?></dd>
    <dt>État</dt>
    <dd><?= $salle->active ? 'Active' : 'Inactive' ?></dd>
</dl>
<p><a href="/salles/<?= (int) $salle->id ?>/edit">Modifier</a></p>
<p><a href="/salles">Retour aux salles</a></p>
