<?php

declare(strict_types=1);

namespace Tests\Unit\Type;

use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use JlacroixDev\PdoRow\Type\MysqlPhpTypeResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MysqlPhpTypeResolverTest extends TestCase
{
    #[DataProvider('nativeTypesProvider')]
    public function testNativeTypeMapping(
        string $databaseType,
        string $expected,
    ): void {
        $resolver = new MysqlPhpTypeResolver();

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

    public static function nativeTypesProvider(): iterable
    {
        yield ['tinyint', 'int'];
        yield ['tinyint(1)', 'int'];
        yield ['smallint', 'int'];
        yield ['mediumint', 'int'];
        yield ['int', 'int'];
        yield ['integer', 'int'];
        yield ['year', 'string'];
        yield ['bigint', 'int|string'];
        yield ['bigint unsigned', 'int|string'];
        yield ['float', 'float'];
        yield ['double', 'float'];
        yield ['real', 'float'];
        yield ['decimal(10,2)', 'string'];
        yield ['numeric(10,2)', 'string'];
        yield ['varchar(255)', 'string'];
        yield ['text', 'string'];
        yield ['json', 'string'];
        yield ['date', 'string'];
        yield ['datetime', 'string'];
        yield ['timestamp', 'string'];
        yield ['time', 'string'];
        yield ['bit(1)', 'int'];
    }

    #[DataProvider('stringifiedTypesProvider')]
    public function testStringification(
        string $databaseType,
    ): void {
        $resolver = new MysqlPhpTypeResolver();

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

    public static function stringifiedTypesProvider(): iterable
    {
        yield ['int'];
        yield ['bigint'];
        yield ['double'];
        yield ['decimal(10,2)'];
        yield ['json'];
    }
}
