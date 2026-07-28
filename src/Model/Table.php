<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Model;

final readonly class Table
{
    /**
     * @param Column[] $columns
     */
    public function __construct(
        public string $name,
        public array $columns,
    ) {
    }
}
