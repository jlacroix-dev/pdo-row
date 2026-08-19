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

    public function resolve(DatabaseColumn $column, FetchTypeConfiguration $configuration): string
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

        $affinity = match ($type) {
            'int' => 'integer',
            'integer' => 'integer',
            'tinyint' => 'integer',
            'smallint' => 'integer',
            'mediumint' => 'integer',
            'bigint' => 'integer',
            'int2' => 'integer',
            'int8' => 'integer',
            'character' => 'text',
            'varchar' => 'text',
            'nchar' => 'text',
            'nvarchar' => 'text',
            'text' => 'text',
            'clob' => 'text',
            'blob' => 'blob',
            'real' => 'real',
            'double' => 'real',
            'float' => 'real',
            'numeric' => 'numeric',
            'decimal' => 'numeric',
            'boolean' => 'numeric',
            'date' => 'numeric',
            'datetime' => 'numeric',
            default => 'text',
        };

        return match ($affinity) {
            'integer' => 'int|float',
            'text' => 'string',
            'blob' => 'string',
            'real' => 'float',
            'numeric' => 'int|float|string',
        };
    }
}
