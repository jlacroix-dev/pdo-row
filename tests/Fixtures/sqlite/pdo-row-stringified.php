<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;
use Tests\Fixtures\TestDatabase;

$pdo = TestDatabase::sqlite(true);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/generated/Stringified')
    ->withNamespace('Tests\\Fixtures\\sqlite\\generated\\Stringified');
