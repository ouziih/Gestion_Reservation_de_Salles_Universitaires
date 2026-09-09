<?php

declare(strict_types=1);

$value = static function (string $field) use ($data): string {
    return htmlspecialchars((string) ($data[$field] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>
<h1>Créer une réservation</h1>
<?php if (isset($errors['general'])): ?>
    <p><?= htmlspecialchars($errors['general'][0], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<form method="post">
    <label>
        Salle
        <select name="salle_id">
            <?php foreach ($salles as $salle): ?>
                <option value="<?= (int) $salle->id ?>" <?= $value('salle_id') === (string) $salle->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle->nom, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['salle_id'])): ?><p><?= htmlspecialchars($errors['salle_id'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Responsable
        <input type="text" name="responsable" value="<?= $value('responsable') ?>">
    </label>
    <?php if (isset($errors['responsable'])): ?><p><?= htmlspecialchars($errors['responsable'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        E-mail
        <input type="email" name="email" value="<?= $value('email') ?>">
    </label>
    <?php if (isset($errors['email'])): ?><p><?= htmlspecialchars($errors['email'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Motif
        <textarea name="motif"><?= $value('motif') ?></textarea>
    </label>
    <?php if (isset($errors['motif'])): ?><p><?= htmlspecialchars($errors['motif'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Début
        <input type="datetime-local" name="date_debut" value="<?= $value('date_debut') ?>">
    </label>
    <?php if (isset($errors['date_debut'])): ?><p><?= htmlspecialchars($errors['date_debut'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Fin
        <input type="datetime-local" name="date_fin" value="<?= $value('date_fin') ?>">
    </label>
    <?php if (isset($errors['date_fin'])): ?><p><?= htmlspecialchars($errors['date_fin'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <input type="hidden" name="statut" value="confirmée">
    <button type="submit">Enregistrer</button>
</form>
