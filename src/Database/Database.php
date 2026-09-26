<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Database;

use JlacroixDev\PdoRow\Model\Table;
use PDO;

interface Database
{
    /**
     * @return Table[]
     */
    public function inspect(PDO $pdo): array;

    public function phpType(
        string $type,
        bool $stringifyFetches,
    ): string;
}
