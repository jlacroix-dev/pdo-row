<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

interface Command
{
    public const int SUCCESS = 0;
    public const int FAILURE = 1;

    public static function name(): string;
    public static function description(): string;

    /**
     * @param string[] $argv
     */
    public function run(array $argv): int;
}