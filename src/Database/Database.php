<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Database;

use JlacroixDev\PdoRow\Model\DatabaseColumn;
use JlacroixDev\PdoRow\Model\Table;
use JlacroixDev\PdoRow\Type\FetchTypeConfiguration;
use PDO;

interface Database
{
    /**
     * @return Table[]
     */
    public function inspect(PDO $pdo): array;

    public function phpType(
        DatabaseColumn $column,
        FetchTypeConfiguration $configuration,
    ): string;
}
