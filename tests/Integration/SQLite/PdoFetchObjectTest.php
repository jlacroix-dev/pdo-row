<?php

declare(strict_types=1);

namespace Tests\Integration\SQLite;

use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\SQLite\Generated\Native\UsersTableRow as NativeUsersTableRow;
use Tests\Fixtures\SQLite\Generated\Native\T1TableRow as NativeT1TableRow;
use Tests\Fixtures\SQLite\Generated\Stringified\UsersTableRow as StringifiedUsersTableRow;
use Tests\Fixtures\SQLite\Generated\Stringified\T1TableRow as StringifiedT1TableRow;
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
        $pdo = TestDatabase::sqlite($stringifyFetches);

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
        $pdo = TestDatabase::sqlite($stringifyFetches);

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
                self::assertSame(
                    get_debug_type($value),
                    // @phpstan-ignore-next-line
                    get_debug_type($tableRow->$property),
                    "Property {$property} has an unexpected runtime type.",
                );
            }
        }
    }

    public static function stringifyFetchesProvider(): iterable
    {
        yield 'Native\UsersTableRow' => ['users', NativeUsersTableRow::class, false];
        yield 'Stringified\UsersTableRow' => ['users', StringifiedUsersTableRow::class, true];
        yield 'Native\T1TableRow' => ['t1', NativeT1TableRow::class, false];
        yield 'Stringified\T1TableRow' => ['t1', StringifiedT1TableRow::class, true];
    }
}
