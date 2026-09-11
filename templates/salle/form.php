<?php

declare(strict_types=1);

$isEdit = $salle !== null;
$value = static function (string $field) use ($salle, $data): string {
    $source = $data !== [] ? $data : ($salle?->getAttributes() ?? []);

    return htmlspecialchars((string) ($source[$field] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>
<h1><?= $isEdit ? 'Modifier une salle' : 'Ajouter une salle' ?></h1>
<?php if (isset($errors['general'])): ?>
    <p><?= htmlspecialchars($errors['general'][0], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<form method="post">
    <label>
        Nom
        <input type="text" name="nom" value="<?= $value('nom') ?>">
    </label>
    <?php if (isset($errors['nom'])): ?><p><?= htmlspecialchars($errors['nom'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Bâtiment
        <input type="text" name="batiment" value="<?= $value('batiment') ?>">
    </label>
    <?php if (isset($errors['batiment'])): ?><p><?= htmlspecialchars($errors['batiment'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Capacité
        <input type="number" name="capacite" value="<?= $value('capacite') ?>">
    </label>
    <?php if (isset($errors['capacite'])): ?><p><?= htmlspecialchars($errors['capacite'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        Type
        <select name="type">
            <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>" <?= $value('type') === $type ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['type'])): ?><p><?= htmlspecialchars($errors['type'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <label>
        <input type="checkbox" name="active" value="1" <?= $value('active') ? 'checked' : '' ?>>
        Active
    </label>
    <?php if (isset($errors['active'])): ?><p><?= htmlspecialchars($errors['active'][0], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <button type="submit">Enregistrer</button>
</form>