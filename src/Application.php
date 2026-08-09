<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Console\Command\Command;
use RuntimeException;

final class Application
{
    /**
     * @var array<string, Command>
     */
    private array $commands = [];

    /**
     * @param Command[] $commands
     */
    public function __construct(array $commands)
    {
        foreach ($commands as $command) {
            $this->commands[$command::name()] = $command;
        }
    }

    /**
     * @param string[] $argv
     */
    public function run(array $argv): int
    {
        $name = $argv[1] ?? 'help';
        if (!array_key_exists($name, $this->commands)) {
            throw new RuntimeException("Unknown command '{$name}'.");
        }
        return $this->commands[$name]->run($argv);
    }
}
