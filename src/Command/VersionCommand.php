<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use JlacroixDev\PdoRow\Version;

final class VersionCommand implements Command
{

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
        echo 'pdo-row ' . Version::VERSION . PHP_EOL;
        return Command::SUCCESS;
    }
}