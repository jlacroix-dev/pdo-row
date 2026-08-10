<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;

$pdo = new PDO(
    'sqlite:' . __DIR__ . '/database.sqlite'
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/generated')
    ->withNamespace('Tests\\Fixtures\\SQLite\\Generated')
    ->onlyTables(['users']);
