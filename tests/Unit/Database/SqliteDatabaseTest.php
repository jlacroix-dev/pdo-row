<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use JlacroixDev\PdoRow\Database\SqliteDatabase;
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

        self::assertSame(
            $expected,
            $database->phpType($databaseType, false),
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

        self::assertSame(
            'string',
            $database->phpType($databaseType, true),
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
