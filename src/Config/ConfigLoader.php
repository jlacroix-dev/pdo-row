<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Config;

use Exception;
use JlacroixDev\PdoRow\Config;
use JlacroixDev\PdoRow\Filesystem\Filesystem;

final class ConfigLoader
{
    public function __construct(
        private readonly Filesystem $filesystem,
    ) {
    }

    public function load(?string $path): Config
    {
        $path ??= getcwd() . '/pdo-row.php';

        if (!$this->filesystem->exists($path)) {
            throw new Exception("Config file '{$path}' not found. Run `pdo-row init` first.");
        }

        $config = $this->filesystem->require($path);
        if (!$config instanceof Config) {
            throw new Exception('Config must be an instance of Config');
        }

        return $config;
    }
}
