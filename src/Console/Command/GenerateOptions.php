<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Console\Command;

final readonly class GenerateOptions
{
    public function __construct(
        public ?string $configuration,
        public bool $help,
    ) {
    }
}
