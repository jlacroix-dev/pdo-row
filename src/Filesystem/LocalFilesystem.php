<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Filesystem;

use RuntimeException;

final class LocalFilesystem implements Filesystem
{
    public function write(string $file, string $contents): void
    {
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), recursive: true);
        }
        file_put_contents($file, $contents);
    }

    public function exists(string $file): bool
    {
        return is_file($file);
    }

    public function ensureDirectory(string $directory): void
    {
        if ($directory === '' || is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(
                "Unable to create directory: {$directory}"
            );
        }
    }

    public function require(string $file): mixed
    {
        return require $file;
    }
}
