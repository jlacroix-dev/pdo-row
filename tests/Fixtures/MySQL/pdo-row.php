<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;
use Tests\Fixtures\TestDatabase;

$pdo = TestDatabase::mysql(false);

return new Config(
    pdo: $pdo,
    directory: __DIR__ . '/Generated/Native',
    namespace: 'Tests\\Fixtures\\MySQL\\Generated\\Native',
);
