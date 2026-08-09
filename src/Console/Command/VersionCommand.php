<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Console\Output;

final readonly class VersionCommand implements Command
{
    public function __construct(
        private string $version,
        private Output $output,
    ) {
    }

    public static function name(): string
    {
        return 'version';
    }

    public static function description(): string
    {
        return 'Display pdo-row version';
    }

    public function run(array $argv): int
    {
        $this->output->write('pdo-row ' . $this->version);
        return Command::SUCCESS;
    }
}
