<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use JlacroixDev\PdoRow\Database\SqliteDatabase;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SqliteDatabaseTest extends TestCase
{
    #[DataProvider('nativeTypesProvider')]
    public function testNativeTypeMapping(
        string $databaseType,
        string $expected,
    ): void {
        $database = new SqliteDatabase();

        $column = new DatabaseColumn(
            name: 'value',
            databaseType: $databaseType,
            nullable: false,
        );

        self::assertSame(
            $expected,
            $database->phpType(
                $column,
                new FetchTypeConfiguration(
                    stringifyFetches: false,
                ),
            ),
        );
    }

    public static function nativeTypesProvider(): iterable
    {
        yield ['INTEGER', 'int|float'];
        yield ['VARCHAR(255)', 'string'];
        yield ['BOOLEAN', 'int|float|string'];
        yield ['DATETIME', 'int|float|string'];
    }

    #[DataProvider('stringifiedTypesProvider')]
    public function testStringification(
        string $databaseType,
    ): void {
        $database = new SqliteDatabase();

        $column = new DatabaseColumn(
            name: 'value',
            databaseType: $databaseType,
            nullable: false,
        );

        self::assertSame(
            'string',
            $database->phpType(
                $column,
                new FetchTypeConfiguration(
                    stringifyFetches: true,
                ),
            ),
        );
    }

    public static function stringifiedTypesProvider(): iterable
    {
        yield ['INTEGER'];
        yield ['VARCHAR(255)'];
        yield ['BOOLEAN'];
        yield ['DATETIME'];
    }
}
