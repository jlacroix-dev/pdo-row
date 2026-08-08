<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console;

class Output
{
    public function write(string $message): void
    {
        echo $message . PHP_EOL;
    }

    public function error(string $message): void
    {
        fwrite(STDERR, $message . PHP_EOL);
    }
}
