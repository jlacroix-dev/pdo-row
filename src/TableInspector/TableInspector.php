<?php

namespace JlacroixDev\PdoRow\TableInspector;

use JlacroixDev\PdoRow\Model\Table;
use PDO;
use RuntimeException;

final class TableInspector
{
    /**
     * @param SchemaInspector[] $inspectors
     */
    public function __construct(
        private array $inspectors,
    )
    {
    }

    /**
     * @return Table[]
     */
    public function inspect(PDO $pdo): array
    {
        foreach ($this->inspectors as $inspector) {
            if ($inspector->supports($pdo)) {
                return $inspector->inspect($pdo);
            }
        }

        throw new RuntimeException(
            sprintf(
                'Unsupported PDO driver: %s',
                $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
            )
        );
    }
}