<?php

declare(strict_types=1);
?>
<h1>404 - Page introuvable</h1>
<p><?= htmlspecialchars($message ?? 'La ressource demandée est introuvable.', ENT_QUOTES, 'UTF-8') ?></p>
