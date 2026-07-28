<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\Table;
use JlacroixDev\PdoRow\Repository\PDO\MySQL\TableRow\ColumnsTableRow;
use JlacroixDev\PdoRow\Repository\PDO\MySQL\TableRow\TablesTableRow;
use PDO;

final class MysqlSchemaInspector implements SchemaInspector
{
    public function driverNameSupported(): string
    {
        return 'mysql';
    }

    public function inspect(PDO $pdo): array
    {
        $sql = <<<SQL
SELECT *
FROM information_schema.tables
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME
SQL;
        $stmt = $pdo->query($sql);
        assert($stmt !== false);

        $tables = [];
        /** @var TablesTableRow[] $rows */
        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, TablesTableRow::class);
        foreach ($rows as $row) {
            $tables[] = new Table(
                name: $row->TABLE_NAME,
                columns: $this->columns($pdo, $row->TABLE_NAME),
            );
        }

        return $tables;
    }

    /**
     * @return Column[]
     */
    private function columns(PDO $pdo, string $table): array
    {
        $sql = <<<SQL
SELECT *
FROM information_schema.columns
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = ?
ORDER BY ORDINAL_POSITION
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$table]);

        $columns = [];

        /** @var ColumnsTableRow[] $rows */
        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, ColumnsTableRow::class);
        foreach ($rows as $row) {
            $columns[] = new Column(
                name: $row->COLUMN_NAME ?? '',
                type: $row->COLUMN_TYPE,
                nullable: $row->IS_NULLABLE === 'YES',
            );
        }

        return $columns;
    }
}
