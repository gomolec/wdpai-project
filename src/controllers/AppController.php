<?php

declare(strict_types=1);

namespace App\Controller;

abstract class AppController
{
    protected function isGet(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET';
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    protected function render(string $template, array $variables = []): void
    {
        $viewsDir = dirname(__DIR__, 2) . '/public/views/';
        $templatePath = $viewsDir . $template . '.html.php';
        $layoutPath = $viewsDir . 'layout.html.php';

        if (!is_file($templatePath)) {
            throw new \RuntimeException('View not found: ' . $template);
        }

        if (!is_file($layoutPath)) {
            throw new \RuntimeException('Layout not found: layout.html.php');
        }

        extract($variables, EXTR_SKIP);

        $stylesheets = $stylesheets ?? [];

        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        require $layoutPath;
    }
}
