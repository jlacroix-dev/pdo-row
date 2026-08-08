<?php

namespace Naming;

use JlacroixDev\PdoRow\Naming\MyNaming;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MyNamingTest extends TestCase
{
    #[DataProvider('testClassDataProvider')]
    public function testClass(string $table, string $expected): void
    {
        $naming = new MyNaming();
        $actual = $naming->class($table);
        self::assertEquals($expected, $actual);
    }

    public static function testClassDataProvider(): array
    {
        return [
            ['user', 'UserTableRow'],
            ['user_roles', 'UserRolesTableRow'],
        ];
    }
}
