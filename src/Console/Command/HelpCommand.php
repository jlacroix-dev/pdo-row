<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

use JlacroixDev\PdoRow\Console\Output;

final readonly class HelpCommand implements Command
{
    /**
     * @param Command[] $commands
     */
    public function __construct(
        private array $commands,
        private Output $output,
    ) {
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
        $help = <<<TEXT
pdo-row

Usage:
  pdo-row <command>

Commands:

TEXT;
        $this->output->write($help);

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
