<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\TableInspector;

use JlacroixDev\PdoRow\Model\Table;

interface SchemaInspector
{
    public function supports(\PDO $pdo): bool;

    /**
     * @return Table[]
     */
    public function inspect(\PDO $pdo): array;
}