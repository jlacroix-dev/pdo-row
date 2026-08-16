<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL\TableRow\Native;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\mysql\generated\Native\NumericTypesTableRow;

use PHPUnit\Framework\TestCase;
use Tests\Traits\PropertyTypeHelper;

class NumericTypesTableRowTest extends TestCase
{
    use PropertyTypeHelper;

    public static function propertyTypeProvider(): iterable
    {
        yield 'bit_col' => ['bit_col', ['int', 'null']];
        yield 'tinyint_col' => ['tinyint_col', ['int', 'null']];
        yield 'bool_col' => ['bool_col', ['int', 'null']];
        yield 'boolean_col' => ['boolean_col', ['int', 'null']];
        yield 'smallint_col' => ['smallint_col', ['int', 'null']];
        yield 'mediumint_col' => ['mediumint_col', ['int', 'null']];
        yield 'int_col' => ['int_col', ['int', 'null']];
        yield 'integer_col' => ['integer_col', ['int', 'null']];
        yield 'bigint_col' => ['bigint_col', ['int', 'string', 'null']];
        yield 'decimal_col' => ['decimal_col', ['string', 'null']];
        yield 'dec_col' => ['dec_col', ['string', 'null']];
        yield 'float_col' => ['float_col', ['float', 'null']];
        yield 'double_col' => ['double_col', ['float', 'null']];
    }

    /**
     * @param string[] $expectedTypes
     */
    #[DataProvider('propertyTypeProvider')]
    public function testPropertyType(string $property, array $expectedTypes): void
    {
        self::assertPropertyType(NumericTypesTableRow::class, $property, $expectedTypes);
    }
}
