<?php

namespace Naming;

use JlacroixDev\PdoRow\Naming\MyNaming;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MyNamingTest extends TestCase
{
    #[DataProvider('dataProvider_testClass')]
    public function testClass(string $table, string $expected): void
    {
        $naming = new MyNaming();
        $actual = $naming->class($table);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testClass(): array
    {
        return [
            ['user', 'UserTableRow'],
            ['user_roles', 'UserRolesTableRow'],
        ];
    }
}
