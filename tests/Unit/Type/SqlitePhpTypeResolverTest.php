<?php

declare(strict_types=1);

namespace Tests\Unit\Type;

use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use JlacroixDev\PdoRow\Type\SqlitePhpTypeResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SqlitePhpTypeResolverTest extends TestCase
{
    #[DataProvider('nativeTypes')]
    public function testNativeTypeMapping(
        string $databaseType,
        string $expected,
    ): void {
        $resolver = new SqlitePhpTypeResolver();

        $column = new DatabaseColumn(
            name: 'value',
            databaseType: $databaseType,
            nullable: false,
        );

        self::assertSame(
            $expected,
            $resolver->resolve(
                $column,
                new FetchTypeConfiguration(
                    stringifyFetches: false,
                ),
            ),
        );
    }

    public static function nativeTypes(): iterable
    {
        yield ['INTEGER', 'int'];
        yield ['VARCHAR(255)', 'string'];
        yield ['BOOLEAN', 'int'];
        yield ['DATETIME', 'string'];
    }

    #[DataProvider('stringifiedTypes')]
    public function testStringification(
        string $databaseType,
    ): void {
        $resolver = new SqlitePhpTypeResolver();

        $column = new DatabaseColumn(
            name: 'value',
            databaseType: $databaseType,
            nullable: false,
        );

        self::assertSame(
            'string',
            $resolver->resolve(
                $column,
                new FetchTypeConfiguration(
                    stringifyFetches: true,
                ),
            ),
        );
    }

    public static function stringifiedTypes(): iterable
    {
        yield ['INTEGER'];
        yield ['VARCHAR(255)'];
        yield ['BOOLEAN'];
        yield ['DATETIME'];
    }
}
