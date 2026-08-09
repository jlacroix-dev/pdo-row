<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config;

$host = env('MYSQL_HOST', '127.0.0.1');
$port = env('MYSQL_PORT', '3306');
$database = env('MYSQL_DATABASE', 'pdo_row_test');
$user = env('MYSQL_USER', 'pdo_row');
$password = env('MYSQL_PASSWORD', 'pdo_row');

$pdo = new PDO(
    "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
    $user,
    $password,
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/generated')
    ->withNamespace('Tests\\Fixtures\\MySQL\\Generated')
    ->onlyTables(['users']);
