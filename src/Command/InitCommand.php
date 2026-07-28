<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use JlacroixDev\PdoRow\Utils\TemplateRenderer;

final class InitCommand implements Command
{
    public function __construct(
        private TemplateRenderer $renderer,
    ){

    }

    public static function name(): string
    {
        return 'init';
    }

    public static function description(): string
    {
        return 'Create a starter configuration file.';
    }

    public function run(array $argv): int
    {
        $filename = getcwd() . '/pdo-row.php';

        if (is_file($filename)) {
            echo 'Configuration already exists.' . PHP_EOL;
            return Command::FAILURE;
        }

        $content = $this->renderer->render(__DIR__ . '/../templates/config.tpl.php');
        file_put_contents($filename, $content);

        echo "Created {$filename}\n";

        return Command::SUCCESS;
    }
}