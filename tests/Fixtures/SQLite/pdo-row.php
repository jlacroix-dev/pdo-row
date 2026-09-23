<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;
use Tests\Fixtures\TestDatabase;

$pdo = TestDatabase::sqlite(false);

return new Config(
    pdo: $pdo,
    directory: __DIR__ . '/Generated/Native',
    namespace: 'Tests\\Fixtures\\SQLite\\Generated\\Native',
);
