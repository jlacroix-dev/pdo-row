<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Model;

final class Column
{
    public function __construct(
        public string $name,
        public string $type,
        public bool   $nullable,
        public ?string $default,
        public string $comment,
    )
    {
    }
}
