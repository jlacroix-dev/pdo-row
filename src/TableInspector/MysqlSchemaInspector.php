<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use Exception;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
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
        if ($stmt === false) {
            throw new Exception('Fail to query DB');
        }

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
     * @return DatabaseColumn[]
     */
    private function columns(PDO $pdo, string $table): array
    {
        $sql = <<<SQL
SELECT *
FROM `{$table}`
LIMIT 0
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $count = $stmt->columnCount();

        for ($i = 0; $i < $count; $i++) {
            $meta = $stmt->getColumnMeta($i);
            $columns[] = new DatabaseColumn(
                name: $meta['name'],
                databaseType: $meta['native_type'],
                nullable: !in_array('not_null', $meta['flags']),
            );
        }

        return $columns;
    }
}
