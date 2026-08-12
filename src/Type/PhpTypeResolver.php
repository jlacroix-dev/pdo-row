<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\DatabaseColumn;

interface PhpTypeResolver
{
    public function driverNameSupported(): string;

    public function resolve(
        DatabaseColumn $column,
        FetchTypeConfiguration $configuration,
    ): string;
}
