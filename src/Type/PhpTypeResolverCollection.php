<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Type;

use JlacroixDev\PdoRow\Model\Column;
use JlacroixDev\PdoRow\Model\DatabaseColumn;
use RuntimeException;

final readonly class PhpTypeResolverCollection
{
    /**
     * @param PhpTypeResolver[] $resolvers
     */
    public function __construct(
        private array $resolvers,
    ) {
    }

    public function resolve(
        string $driverName,
        DatabaseColumn $column,
        FetchTypeConfiguration $configuration,
    ): string {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->driverNameSupported() === $driverName) {
                return $resolver->resolve(
                    new DatabaseColumn(
                        name: $column->name,
                        databaseType: $column->databaseType,
                        nullable: $column->nullable,
                    ),
                    $configuration,
                );
            }
        }

        throw new RuntimeException(
            "Unsupported PDO type resolver: {$driverName}"
        );
    }
}
