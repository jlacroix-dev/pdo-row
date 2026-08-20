<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Console\Output;
use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Template\TemplateRenderer;

final readonly class InitCommand implements Command
{
    public function __construct(
        private Filesystem $filesystem,
        private Output $output,
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
            $this->output->write('Configuration already exists');
            return Command::FAILURE;
        }

        $source = __DIR__ . '/../../../../templates/pdo-row.tpl.php';
        $this->filesystem->copy($source, $filename);

        $this->output->write("Created {$filename}");

        return Command::SUCCESS;
    }
}
