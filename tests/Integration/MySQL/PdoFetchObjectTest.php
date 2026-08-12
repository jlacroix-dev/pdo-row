<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL;

use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\MySQL\Generated\Native\UsersTableRow as NativeUsersTableRow;
use Tests\Fixtures\MySQL\Generated\Stringified\UsersTableRow as StringifiedUsersTableRow;
use Tests\Fixtures\TestDatabase;

final class PdoFetchObjectTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $generated = __DIR__ . '/../../Fixtures/mysql/generated/Native/UsersTableRow.php';
        self::assertFileExists(
            $generated,
            'Generated TableRow does not exist. Run: composer test:integration:mysql:setup'
        );
        require_once $generated;

        self::assertTrue(
            class_exists(NativeUsersTableRow::class),
            NativeUsersTableRow::class . ' was not generated.'
        );

        $generated = __DIR__ . '/../../Fixtures/mysql/generated/Stringified/UsersTableRow.php';
        self::assertFileExists(
            $generated,
            'Generated TableRow does not exist. Run: composer test:integration:mysql:setup'
        );
        require_once $generated;

        self::assertTrue(
            class_exists(StringifiedUsersTableRow::class),
            StringifiedUsersTableRow::class . ' was not generated.'
        );
    }

    /**
     * @param class-string<object> $rowClass
     */
    #[DataProvider('stringifyFetchesProvider')]
    public function testFetchObject(
        string $rowClass,
        bool $stringifyFetches,
    ): void {
        $pdo = TestDatabase::mysql($stringifyFetches);

        $sql = 'SELECT * FROM users LIMIT 1';

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
            self::assertSame(
                get_debug_type($value),
                // @phpstan-ignore-next-line
                get_debug_type($tableRow->$property),
                "Property {$property} has an unexpected runtime type.",
            );
        }
    }

    /**
     * @param class-string<object> $rowClass
     */
    #[DataProvider('stringifyFetchesProvider')]
    public function testFetchAllObject(
        string $rowClass,
        bool $stringifyFetches,
    ): void {
        $pdo = TestDatabase::mysql($stringifyFetches);

        $sql = <<<'SQL'
SELECT *
FROM users
ORDER BY id
SQL;

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        /** @var stdClass[] $rows */
        $rows = $statement->fetchAll(PDO::FETCH_CLASS);

        $statement = $pdo->query($sql);
        self::assertNotFalse($statement);
        /** @var object[] $tableRows */
        $tableRows = $statement->fetchAll(PDO::FETCH_CLASS, $rowClass);

        self::assertCount(2, $rows);

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
        yield 'native' => [
            NativeUsersTableRow::class,
            false,
        ];

        yield 'stringified' => [
            StringifiedUsersTableRow::class,
            true,
        ];
    }
}
