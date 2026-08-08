<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Naming;

interface NamingStrategy
{
    public function class(string $table): string;
}
