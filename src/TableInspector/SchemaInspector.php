<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use JlacroixDev\PdoRow\Model\Table;
use PDO;

interface SchemaInspector
{
    public function driverNameSupported(): string;

    /**
     * @return Table[]
     */
    public function inspect(PDO $pdo): array;
}