<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Filesystem;

interface Filesystem
{
    public function write(string $file, string $contents): void;

    public function exists(string $file): bool;

    public function ensureDirectory(string $directory): void;

    public function require(string $file): mixed;
}
