<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Command;

final readonly class GenerateOptions
{
    public function __construct(
        public ?string $configuration,
        public bool $help,
    ) {
    }
}
