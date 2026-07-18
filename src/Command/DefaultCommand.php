<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

final class DefaultCommand
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public function run(): int
    {
        echo 'PDO Row test' . PHP_EOL;
        return self::SUCCESS;
    }
}