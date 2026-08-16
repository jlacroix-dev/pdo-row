<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

use JlacroixDev\PdoRow\Model\DatabaseColumn;

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

        $type = strtolower(
            preg_replace(
                '/\(.*/',
                '',
                $column->databaseType
            ) ?? $column->databaseType
        );

        if (str_ends_with($type, ' unsigned')) {
            $type = substr($type, 0, -9);
        }

        return match ($type) {
            'bit',
            'tinyint',
            'smallint',
            'mediumint',
            'int',
            'integer' => 'int',
            'year' => 'string',

            'bigint' => 'int|string',

            'float',
            'double',
            'real' => 'float',

            default => 'string',
        };
    }
}
