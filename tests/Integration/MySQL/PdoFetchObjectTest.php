<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL;

use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\MySQL\Generated\UsersTableRow;

final class PdoFetchObjectTest extends TestCase
{
    private static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $generated = __DIR__ . '/../../Fixtures/mysql/generated/UsersTableRow.php';

        self::assertFileExists(
            $generated,
            'Generated TableRow does not exist. Run: composer test:integration:mysql:setup'
        );

        require_once $generated;

        self::assertTrue(
            class_exists(UsersTableRow::class),
            'UsersTableRow was not generated.'
        );

        $host = env('MYSQL_HOST', '127.0.0.1');
        $port = env('MYSQL_PORT', '3306');
        $database = env('MYSQL_DATABASE', 'pdo_row_test');
        $user = env('MYSQL_USER', 'pdo_row');
        $password = env('MYSQL_PASSWORD', 'pdo_row');

        self::$pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
            $user,
            $password,
        );

        self::$pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    public function testFetchObjectHydratesGeneratedRow(): void
    {
        $statement = self::$pdo->query(<<<'SQL'
            SELECT *
            FROM users
            WHERE id = 1
        SQL);

        self::assertNotFalse($statement);

        $row = $statement->fetchObject(UsersTableRow::class);

        self::assertInstanceOf(UsersTableRow::class, $row);

        self::assertSame('1', $row->id);
        self::assertSame('John Doe', $row->name);
        self::assertSame('john@example.com', $row->email);
        self::assertSame('1', $row->active);
        self::assertNull($row->nickname);
        self::assertSame('2026-08-08 12:00:00', $row->created_at);
    }

    public function testFetchAllHydratesGeneratedRows(): void
    {
        $statement = self::$pdo->query(<<<'SQL'
            SELECT *
            FROM users
            ORDER BY id
        SQL);

        self::assertNotFalse($statement);

        $rows = $statement->fetchAll(
            PDO::FETCH_CLASS,
            UsersTableRow::class
        );

        self::assertCount(2, $rows);

        self::assertInstanceOf(
            UsersTableRow::class,
            $rows[0]
        );

        self::assertInstanceOf(
            UsersTableRow::class,
            $rows[1]
        );

        self::assertSame('1', $rows[0]->id);
        self::assertSame('John Doe', $rows[0]->name);
        self::assertSame('john@example.com', $rows[0]->email);
        self::assertSame('1', $rows[0]->active);
        self::assertNull($rows[0]->nickname);

        self::assertSame('2', $rows[1]->id);
        self::assertSame('Jane Doe', $rows[1]->name);
        self::assertSame('jane@example.com', $rows[1]->email);
        self::assertSame('0', $rows[1]->active);
        self::assertSame('Jane', $rows[1]->nickname);
        self::assertSame('2026-08-08 13:00:00', $rows[1]->created_at);
    }
}
