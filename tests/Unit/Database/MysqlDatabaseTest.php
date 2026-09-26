<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use JlacroixDev\PdoRow\Database\MysqlDatabase;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MysqlDatabaseTest extends TestCase
{
    #[DataProvider('nativeTypesProvider')]
    public function testNativeTypeMapping(
        string $databaseType,
        string $expected,
    ): void {
        $database = new MysqlDatabase();

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
        yield ['STRING', 'string'];
        yield ['VAR_STRING', 'string'];
        yield ['DATE', 'string'];
        yield ['DATETIME', 'string'];
        yield ['TIME', 'string'];
        yield ['TIMESTAMP', 'string'];
        yield ['YEAR', 'string'];
        yield ['TINY', 'int'];
        yield ['SHORT', 'int'];
        yield ['INT24', 'int'];
        yield ['LONG', 'int'];
        yield ['LONGLONG', 'int|string'];
        yield ['NEWDECIMAL', 'string'];
        yield ['FLOAT', 'float'];
        yield ['DOUBLE', 'float'];
        yield ['BLOB', 'string'];
        yield ['BIT', 'int'];
    }

    #[DataProvider('stringifiedTypesProvider')]
    public function testStringification(
        string $databaseType,
    ): void {
        $database = new MysqlDatabase();

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
        yield ['STRING'];
        yield ['TINY'];
        yield ['INT24'];
        yield ['FLOAT'];
    }
}
