<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Model;

final readonly class DatabaseColumn
{
    public function __construct(
        public string $name,
        public string $databaseType,
        public bool $nullable,
    ) {
    }
}
