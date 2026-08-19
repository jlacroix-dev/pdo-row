<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL\TableRow\Native;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\MySQL\Generated\Native\DateAndTimeTypesTableRow;
use PHPUnit\Framework\TestCase;
use Tests\Traits\PropertyTypeHelper;

class DateAndTimeTypesTableRowTest extends TestCase
{
    use PropertyTypeHelper;

    public static function propertyTypeProvider(): iterable
    {
        yield 'date_col' => ['date_col', ['string', 'null']];
        yield 'datetime_col' => ['datetime_col', ['string', 'null']];
        yield 'timestamp_col' => ['timestamp_col', ['string', 'null']];
        yield 'time_col' => ['time_col', ['string', 'null']];
        yield 'year_col' => ['year_col', ['string', 'null']];
    }

    /**
     * @param string[] $expectedTypes
     */
    #[DataProvider('propertyTypeProvider')]
    public function testPropertyType(string $property, array $expectedTypes): void
    {
        self::assertPropertyType(DateAndTimeTypesTableRow::class, $property, $expectedTypes);
    }
}
