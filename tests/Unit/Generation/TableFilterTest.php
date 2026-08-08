<?php

namespace Tests\Unit\Generation;

use InvalidArgumentException;
use JlacroixDev\PdoRow\Generation\TableFilter;
use JlacroixDev\PdoRow\Model\Table;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TableFilterTest extends TestCase
{
    /**
     * @param Table[] $tables
     * @param string[]|null $onlyTables
     * @param string[]|null $exceptTables
     * @param string[] $expected
     */
    #[DataProvider('dataProvider_testFilter')]
    public function testFilter(
        array  $tables,
        ?array $onlyTables,
        ?array $exceptTables,
        array  $expected,
    ): void
    {
        $tableFilter = new TableFilter();
        $actual = $tableFilter->filter($tables, $onlyTables, $exceptTables);

        $actualTableNames = array_map(fn(Table $table): string => $table->name, $actual);
        self::assertArraysAreEqual($expected, $actualTableNames);
    }

    public static function dataProvider_testFilter(): array
    {
        $tables = [
            new Table('users', []),
            new Table('user_roles', []),
            new Table('roles', []),
        ];

        return [
            'all tables without filters' => [
                'tables' => $tables,
                'onlyTables' => null,
                'exceptTables' => null,
                'expected' => ['users', 'user_roles', 'roles'],
            ],
            'applies only filter' => [
                'tables' => $tables,
                'onlyTables' => ['users', 'roles'],
                'exceptTables' => null,
                'expected' => ['users', 'roles'],
            ],
            'applies except filter' => [
                'tables' => $tables,
                'onlyTables' => null,
                'exceptTables' => ['user_roles', 'roles'],
                'expected' => ['users'],
            ],
        ];
    }

    public function testFilterCanNotUseBothOnlyTablesAndExceptTablesTogether(): void
    {
        $tables = [
            new Table('users', []),
        ];
        $onlyTables = [
            'users',
            'roles',
        ];
        $exceptTables = [
            'user_roles',
            'roles',
        ];
        $tableFilter = new TableFilter();

        $this->expectException(InvalidArgumentException::class);
        $tableFilter->filter($tables, $onlyTables, $exceptTables);
    }
}
