<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Config;

use PDO;

final readonly class Config
{
    public function __construct(
        public PDO $pdo,
        public string $directory = 'src/Repository/PDO/TableRow',
        public string $namespace = 'App\\Repository\\PDO\\TableRow',
    ) {
    }

    public function __toString(): string
    {
        $phpVersion = phpversion();
        $driverName = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $serverVersion = $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        return <<<TXT
# Config
PHP Version: $phpVersion
Database: $driverName $serverVersion
Directory: $this->directory
Namespace: $this->namespace

TXT;
    }
}
