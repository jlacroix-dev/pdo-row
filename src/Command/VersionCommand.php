<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

final class VersionCommand implements Command
{
    public function __construct(
        private readonly string $version,
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
        echo 'pdo-row ' . $this->version . PHP_EOL;
        return Command::SUCCESS;
    }
}
