<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use Composer\InstalledVersions;

final class Package
{
    private const NAME = 'jlacroix-dev/pdo-row';

    public static function name(): string
    {
        return self::NAME;
    }

    public static function version(): string
    {
        return InstalledVersions::getPrettyVersion(self::NAME) ?? 'dev';
    }
}
