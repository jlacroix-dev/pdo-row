<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\Table;
use PDO;

final class SqliteSchemaInspector implements SchemaInspector
{
    public function supports(PDO $pdo): bool
    {
        return $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';
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
        $statement = $pdo->query(
            $sql
        );

        foreach ($statement->fetchAll(PDO::FETCH_COLUMN) as $name) {
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
        $statement = $pdo->query(
            "PRAGMA table_info('{$table}')"
        );

        $columns = [];

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $column) {
            $columns[] = new Column(
                name: $column['name'],
                type: $column['type'],
                nullable: (int) $column['notnull'] === 0,
            );
        }

        return $columns;
    }
}
