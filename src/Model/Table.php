<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Model;

final readonly class Table
{
    /**
     * @param DatabaseColumn[] $columns
     */
    public function __construct(
        public string $name,
        public array $columns,
    ) {
    }
}
