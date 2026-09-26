<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Database;

use Exception;
use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\Table;
use PDO;
use RuntimeException;

final class MysqlDatabase implements Database
{
    public function inspect(PDO $pdo): array
    {
        $sql = <<<SQL
SELECT TABLE_NAME
FROM information_schema.tables
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME
SQL;
        $stmt = $pdo->query($sql);
        if ($stmt === false) {
            throw new Exception('Fail to query DB');
        }

        $tables = [];
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
        $stringifyFetches = (bool)$pdo->getAttribute(PDO::ATTR_STRINGIFY_FETCHES);

        $sql = <<<SQL
SELECT *
FROM `{$table}`
LIMIT 0
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $count = $stmt->columnCount();

        $columns = [];
        for ($i = 0; $i < $count; $i++) {
            $meta = $stmt->getColumnMeta($i);
            if ($meta === false) {
                throw new RuntimeException('Not able to get column meta');
            }

            $name = $meta['name'];
            $databaseType = $meta['native_type'] ?? '';
            $phpType = $this->phpType($databaseType, $stringifyFetches);
            $nullable = !in_array('not_null', $meta['flags'], true);

            $columns[] = new Column($name, $databaseType, $phpType, $nullable);
        }

        return $columns;
    }

    public function phpType(
        string $type,
        bool $stringifyFetches,
    ): string {
        if ($stringifyFetches) {
            return 'string';
        }

        return match ($type) {
            'STRING' => 'string',
            'VAR_STRING' => 'string',
            'DATE' => 'string',
            'DATETIME' => 'string',
            'TIME' => 'string',
            'TIMESTAMP' => 'string',
            'YEAR' => 'string',
            'TINY' => 'int',
            'SHORT' => 'int',
            'INT24' => 'int',
            'LONG' => 'int',
            'LONGLONG' => 'int|string',
            'NEWDECIMAL' => 'string',
            'FLOAT' => 'float',
            'DOUBLE' => 'float',
            'BLOB' => 'string',
            'BIT' => 'int',

            default => throw new RuntimeException('Unsuported type'),
        };
    }
}
