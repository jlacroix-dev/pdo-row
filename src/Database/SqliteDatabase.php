<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Database;

use Exception;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Model\Table;
use PDO;

final class SqliteDatabase implements Database
{
    public function inspect(PDO $pdo): array
    {
        $tables = [];

        $sql = <<<SQL
SELECT name
FROM sqlite_master
WHERE type = 'table'
AND name NOT LIKE 'sqlite_%'
SQL;
        $stmt = $pdo->query($sql);
        if ($stmt === false) {
            throw new Exception('Fail to query DB');
        }

        /** @var string[] $names */
        $names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($names as $name) {
            $tables[] = new Table(
                name: $name,
                columns: $this->columns($pdo, $name),
            );
        }

        return $tables;
    }

    /**
     * @return DatabaseColumn[]
     */
    private function columns(PDO $pdo, string $table): array
    {
        $stmt = $pdo->query(
            "PRAGMA table_info('{$table}')"
        );
        if ($stmt === false) {
            throw new Exception('Fail to query DB');
        }

        $columns = [];

        /**
         * @var array{
         *     name: string,
         *     type: string,
         *     notnull: string,
         * }[] $rows
         */
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $columns[] = new DatabaseColumn(
                name: $row['name'],
                databaseType: $row['type'],
                nullable: (int) $row['notnull'] === 0,
            );
        }

        return $columns;
    }

    public function phpType(
        DatabaseColumn $column,
        bool $stringifyFetches,
    ): string {
        if ($stringifyFetches) {
            return 'string';
        }

        $type = strtolower(
            preg_replace(
                '/\(.*/',
                '',
                $column->databaseType
            ) ?? $column->databaseType
        );

        $affinity = match ($type) {
            'int' => 'integer',
            'integer' => 'integer',
            'tinyint' => 'integer',
            'smallint' => 'integer',
            'mediumint' => 'integer',
            'bigint' => 'integer',
            'int2' => 'integer',
            'int8' => 'integer',
            'character' => 'text',
            'varchar' => 'text',
            'nchar' => 'text',
            'nvarchar' => 'text',
            'text' => 'text',
            'clob' => 'text',
            'blob' => 'blob',
            'real' => 'real',
            'double' => 'real',
            'float' => 'real',
            'numeric' => 'numeric',
            'decimal' => 'numeric',
            'boolean' => 'numeric',
            'date' => 'numeric',
            'datetime' => 'numeric',
            default => 'text',
        };

        return match ($affinity) {
            'integer' => 'int|float',
            'text' => 'string',
            'blob' => 'string',
            'real' => 'float',
            'numeric' => 'int|float|string',
        };
    }
}
