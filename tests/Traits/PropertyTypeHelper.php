<?php

declare(strict_types=1);

namespace Tests\Traits;

use Exception;
use PHPUnit\Framework\Assert;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use RuntimeException;

trait PropertyTypeHelper
{
    /**
     * @param class-string<object> $rowClass
     * @param string[] $expectedTypes
     */
    public static function assertPropertyType(
        string $rowClass,
        string $property,
        array $expectedTypes,
    ): void {
        $actual = self::getPropertyType($rowClass, $property);
        Assert::assertEqualsCanonicalizing($expectedTypes, $actual);
    }

    /**
     * @param class-string<object> $rowClass
     * @return string[]
     */
    private static function getPropertyType(string $rowClass, string $property): array
    {
        $property = new ReflectionProperty($rowClass, $property);
        $type = $property->getType();

        if ($type === null) {
            // not property type
            return [];
        }
        if ($type instanceof ReflectionNamedType) {
            $typeName = $type->getName();
            return $type->allowsNull()
                ? ['null', $typeName]
                : [];
        }
        if ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
            return array_map(function (ReflectionNamedType $type) {
                return $type->getName();
            }, $types);
        }
        if ($type instanceof ReflectionIntersectionType) {
            throw new Exception('Not implemented yet');
        }
        throw new RuntimeException('Unhandled type');
    }
}