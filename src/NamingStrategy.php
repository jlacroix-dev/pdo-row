<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

interface NamingStrategy
{
    public function class(string $table): string;
}