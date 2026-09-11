<?php

declare(strict_types=1);

namespace App\View;

final class Renderer
{
    public function __construct(
        private readonly string $templatesDir,
    ) {
    }

    public function render(string $template, array $data, int $status = 200): string
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);
        ob_start();
        require $this->templatesDir . '/' . $template;
        $content = (string) ob_get_clean();

        ob_start();
        require $this->templatesDir . '/layout/base.php';

        return (string) ob_get_clean();
    }

    public function redirect(string $location): string
    {
        header('Location: ' . $location, true, 303);

        return '';
    }
}