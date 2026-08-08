<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Generation;

final readonly class GeneratedFile
{
    public function __construct(
        public string $filename,
        public string $contents,
    ) {
    }
}
