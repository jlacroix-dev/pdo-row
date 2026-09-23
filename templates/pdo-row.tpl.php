<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config\Config;

$host = getenv('DB_HOST');
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

return new Config(
    pdo: $pdo,
    directory: __DIR__ . '/src/Repository/PDO/TableRow',
    namespace: 'App\\Repository\\PDO\\TableRow',
);
