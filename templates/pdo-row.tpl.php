<?php

declare(strict_types=1);

use JlacroixDev\PdoRow\Config;

$host = getenv('DB_HOST');
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

return Config::configure($pdo)
    ->withDirectory(__DIR__ . '/src/Repository/PDO/TableRow')
    ->withNamespace('App\\Repository\\PDO\\TableRow')

//    ->withNamingStrategy(new JlacroixDev\PdoRow\Naming\MyNaming())
//    ->withTemplate(__DIR__ . '/templates/class.tpl.php')
//    ->onlyTables(['TABLES', 'COLUMNS']);
//    ->exceptTables(['FILES'])
    ;
