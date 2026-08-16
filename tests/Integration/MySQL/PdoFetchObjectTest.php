<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL;

use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use stdClass;
use Tests\Fixtures\MySQL\Generated\Native;
use Tests\Fixtures\MySQL\Generated\Stringified;
use Tests\Fixtures\TestDatabase;

final class PdoFetchObjectTest extends TestCase
{
    /**
     * @param class-string<object> $rowClass
     */
    #[DataProvider('stringifyFetchesProvider')]
    public function testFetchObject(
        string $table,
        string $rowClass,
        bool $stringifyFetches,
    ): void {
        $pdo = TestDatabase::mysql($stringifyFetches);

        $sql = <<<SQL
SELECT *
FROM {$table}
LIMIT 1
SQL;

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        $stdClass = $statement->fetchObject();
        self::assertNotFalse($stdClass);

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        $tableRow = $statement->fetchObject($rowClass);
        self::assertNotFalse($tableRow);

        self::assertInstanceOf($rowClass, $tableRow);

        foreach (get_object_vars($stdClass) as $property => $value) {
            $stdClassValue = $value;
            // @phpstan-ignore-next-line
            $tableRowValue = $tableRow->$property;

            self::assertSame(
                get_debug_type($stdClassValue),
                get_debug_type($tableRowValue),
                "Property {$property} has an unexpected runtime type.",
            );

            self::assertSame(
                $stdClassValue,
                $tableRowValue,
                "Property {$property} has an unexpected value.",
            );
        }
    }

    /**
     * @param class-string<object> $rowClass
     */
    #[DataProvider('stringifyFetchesProvider')]
    public function testFetchAllObject(
        string $table,
        string $rowClass,
        bool $stringifyFetches,
    ): void {
        $pdo = TestDatabase::mysql($stringifyFetches);

        $sql = <<<SQL
SELECT *
FROM {$table}
SQL;

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        /** @var stdClass[] $rows */
        $rows = $statement->fetchAll(PDO::FETCH_CLASS);

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        /** @var object[] $tableRows */
        $tableRows = $statement->fetchAll(PDO::FETCH_CLASS, $rowClass);

        $count = count($rows);
        for ($i = 0; $i < $count; ++$i) {
            $stdClass = $rows[$i];
            $tableRow = $tableRows[$i];

            self::assertInstanceOf($rowClass, $tableRow);

            foreach (get_object_vars($stdClass) as $property => $value) {
                $stdClassValue = $value;
                // @phpstan-ignore-next-line
                $tableRowValue = $tableRow->$property;

                self::assertSame(
                    get_debug_type($stdClassValue),
                    get_debug_type($tableRowValue),
                    "Property {$property} has an unexpected runtime type.",
                );

                self::assertSame(
                    $stdClassValue,
                    $tableRowValue,
                    "Property {$property} has an unexpected value.",
                );
            }
        }
    }

    public static function stringifyFetchesProvider(): iterable
    {
        yield 'Native\UsersTableRow' => ['users', Native\UsersTableRow::class, false];
        yield 'Stringified\UsersTableRow' => ['users', Stringified\UsersTableRow::class, true];
        yield 'Native\NumericTypesTableRow' => ['numeric_types', Native\NumericTypesTableRow::class, false];
        yield 'Stringified\NumericTypesTableRow' => ['numeric_types', Stringified\NumericTypesTableRow::class, true];
        yield 'Native\DateAndTimeTypesTableRow' => [
            'date_and_time_types',
            Native\DateAndTimeTypesTableRow::class,
            false
        ];
        yield 'Stringified\DateAndTimeTypesTableRow' => [
            'date_and_time_types',
            Stringified\DateAndTimeTypesTableRow::class,
            true
        ];
    }
}
