<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Generation;

use InvalidArgumentException;
use JlacroixDev\PdoRow\Model\Table;

final class TableFilter
{
    /**
     * @param Table[] $tables
     * @param string[]|null $onlyTables
     * @param string[]|null $exceptTables
     *
     * @return Table[]
     */
    public function filter(
        array  $tables,
        ?array $onlyTables,
        ?array $exceptTables,
    ): array
    {
        if (!is_null($onlyTables) && !is_null($exceptTables)) {
            throw new InvalidArgumentException('Can not use onlyTables and exceptTables at the same time');
        }

        if (!is_null($onlyTables)) {
            return array_values(array_filter($tables, function (Table $table) use ($onlyTables): bool {
                return in_array($table->name, $onlyTables, true);
            }));
        }

        if (!is_null($exceptTables)) {
            return array_values(array_filter($tables, function (Table $table) use ($exceptTables): bool {
                return !in_array($table->name, $exceptTables, true);
            }));
        }

        return $tables;
    }
}
