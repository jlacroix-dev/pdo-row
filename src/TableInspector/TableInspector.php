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
        private readonly array $inspectors,
    ) {
    }

    /**
     * @return Table[]
     */
    public function inspect(PDO $pdo): array
    {
        /** @var string $driverName */
        $driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        foreach ($this->inspectors as $inspector) {
            if ($inspector->driverNameSupported() === $driverName) {
                return $inspector->inspect($pdo);
            }
        }

        throw new RuntimeException("Unsupported PDO driver: $driverName");
    }
}
