<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use Exception;
use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\Table;
use PDO;

final class SqliteSchemaInspector implements SchemaInspector
{
    public function driverNameSupported(): string
    {
        return 'sqlite';
    }

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
     * @return Column[]
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
            $columns[] = new Column(
                name: $row['name'],
                type: $row['type'],
                nullable: (int) $row['notnull'] === 0,
            );
        }

        return $columns;
    }
}
