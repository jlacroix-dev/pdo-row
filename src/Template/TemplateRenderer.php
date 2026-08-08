<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Template;

final class TemplateRenderer
{
    /**
     * @param array<string, mixed> $variables
     */
    public function render(string $template, array $variables = []): string
    {
        extract($variables, EXTR_SKIP);
        ob_start();
        require $template;
        return ob_get_clean();
    }
}
