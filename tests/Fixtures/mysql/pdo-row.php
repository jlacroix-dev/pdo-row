<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;
use Tests\Fixtures\TestDatabase;

$pdo = TestDatabase::mysql(false);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/generated/Native')
    ->withNamespace('Tests\\Fixtures\\MySQL\\Generated\\Native')
    ->onlyTables(['users']);
