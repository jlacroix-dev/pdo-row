<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;
use Tests\Fixtures\TestDatabase;

$pdo = TestDatabase::sqlite(false);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/generated/Native')
    ->withNamespace('Tests\\Fixtures\\sqlite\\generated\\Native');
