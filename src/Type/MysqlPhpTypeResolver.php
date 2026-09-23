<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

use JlacroixDev\PdoRow\Model\DatabaseColumn;
use RuntimeException;

final class MysqlPhpTypeResolver implements PhpTypeResolver
{
    public function driverNameSupported(): string
    {
        return 'mysql';
    }

    public function resolve(
        DatabaseColumn $column,
        FetchTypeConfiguration $configuration,
    ): string {
        if ($configuration->stringifyFetches) {
            return 'string';
        }

        $type = $column->databaseType;
        $phpType = match ($type) {
            'STRING' => 'string',
            'VAR_STRING' => 'string',
            'DATE' => 'string',
            'DATETIME' => 'string',
            'TIME' => 'string',
            'TIMESTAMP' => 'string',
            'YEAR' => 'string',
            'TINY' => 'int',
            'SHORT' => 'int',
            'INT24' => 'int',
            'LONG' => 'int',
            'LONGLONG' => 'int|string',
            'NEWDECIMAL' => 'string',
            'FLOAT' => 'float',
            'DOUBLE' => 'float',
            'BLOB' => 'string',
            'BIT' => 'int',

            default => throw new RuntimeException('Unsuported type'),
        };

        return $phpType;
    }
}
