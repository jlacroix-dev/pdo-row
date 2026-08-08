<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use JlacroixDev\PdoRow\Template\TemplateRenderer;
use JlacroixDev\PdoRow\Utils\Filesystem;

final class InitCommand implements Command
{
    public function __construct(
        private TemplateRenderer $renderer,
        private Filesystem $filesystem,
    ) {
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

        if ($this->filesystem->exists($filename)) {
            echo 'Configuration already exists.' . PHP_EOL;
            return Command::FAILURE;
        }

        $content = $this->renderer->render(__DIR__ . '/../../templates/pdo-row.tpl.php');
        $this->filesystem->write($filename, $content);

        echo "Created {$filename}\n";

        return Command::SUCCESS;
    }
}
