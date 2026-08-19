<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use PDO;

final class TestDatabase
{
    public static function sqlite(bool $stringifyFetches): PDO
    {
        return self::create(
            dsn: 'sqlite:' . __DIR__ . '/SQLite/database.sqlite',
            stringifyFetches: $stringifyFetches,
        );
    }

    public static function mysql(bool $stringifyFetches): PDO
    {
        return self::create(
            dsn: sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                self::mysqlHost(),
                self::mysqlPort(),
                self::mysqlDatabase(),
            ),
            username: self::mysqlUser(),
            password: self::mysqlPassword(),
            stringifyFetches: $stringifyFetches,
        );
    }

    private static function create(
        string $dsn,
        ?string $username = null,
        ?string $password = null,
        bool $stringifyFetches = false,
    ): PDO {
        $pdo = new PDO($dsn, $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, $stringifyFetches);

        return $pdo;
    }

    private static function mysqlHost(): string
    {
        return env('MYSQL_HOST', '127.0.0.1');
    }

    private static function mysqlPort(): string
    {
        return env('MYSQL_PORT', '3306');
    }

    private static function mysqlDatabase(): string
    {
        return env('MYSQL_DATABASE', 'pdo_row_test');
    }

    private static function mysqlUser(): string
    {
        return env('MYSQL_USER', 'pdo_row');
    }

    private static function mysqlPassword(): string
    {
        return env('MYSQL_PASSWORD', 'pdo_row');
    }
}
