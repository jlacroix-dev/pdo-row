<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL\TableRow\Native;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\MySQL\Generated\Native\StringTypesTableRow;
use Tests\Traits\PropertyTypeHelper;

class StringTypesTableRowTest extends TestCase
{
    use PropertyTypeHelper;

    public static function propertyTypeProvider(): iterable
    {
        yield 'char_col' => ['char_col', ['string', 'null']];
        yield 'varchar_col' => ['varchar_col', ['string', 'null']];
        yield 'binary_col' => ['binary_col', ['string', 'null']];
        yield 'varbinary_col' => ['varbinary_col', ['string', 'null']];
        yield 'blob_col' => ['blob_col', ['string', 'null']];
        yield 'text_col' => ['text_col', ['string', 'null']];
        yield 'enum_col' => ['enum_col', ['string', 'null']];
        yield 'set_col' => ['set_col', ['string', 'null']];
    }

    /**
     * @param string[] $expectedTypes
     */
    #[DataProvider('propertyTypeProvider')]
    public function testPropertyType(string $property, array $expectedTypes): void
    {
        self::assertPropertyType(StringTypesTableRow::class, $property, $expectedTypes);
    }
}
