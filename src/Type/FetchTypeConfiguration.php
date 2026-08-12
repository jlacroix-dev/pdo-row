<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

final readonly class FetchTypeConfiguration
{
    public function __construct(
        public bool $stringifyFetches,
    ) {
    }
}
