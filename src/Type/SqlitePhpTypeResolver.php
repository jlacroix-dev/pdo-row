<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

use JlacroixDev\PdoRow\Model\DatabaseColumn;

final class SqlitePhpTypeResolver implements PhpTypeResolver
{
    public function driverNameSupported(): string
    {
        return 'sqlite';
    }

    public function resolve(DatabaseColumn $column, FetchTypeConfiguration $configuration,): string
    {
        if ($configuration->stringifyFetches) {
            return 'string';
        }

        $type = strtolower(
            preg_replace(
                '/\(.*/',
                '',
                $column->databaseType
            ) ?? $column->databaseType
        );

        return match ($type) {
            'boolean' => 'int',
            'integer' => 'int',
            'real' => 'float',
            'text' => 'string',
            'blob' => 'string',
            default => 'string',
        };
    }
}
