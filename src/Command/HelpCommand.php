<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

use JlacroixDev\PdoRow\Command\Command;

final class HelpCommand implements Command
{
    /**
     * @param Command[] $commands
     */
    public function __construct(
        private readonly array $commands
    )
    {
    }

    public static function name(): string
    {
        return 'help';
    }

    public static function description(): string
    {
        return 'Display this help message.';
    }

    public function run(array $argv): int
    {
        echo <<<TEXT
pdo-row

Usage:
  pdo-row <command>

Commands:

TEXT;

        foreach ($this->commands as $command) {
            printf(
                "  %-12s %s\n",
                $command::name(),
                $command::description(),
            );
        }
        printf(
            "  %-12s %s\n",
            self::name(),
            self::description(),
        );
        return Command::SUCCESS;
    }

}