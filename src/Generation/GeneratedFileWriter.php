<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Generation;

use JlacroixDev\PdoRow\Filesystem\Filesystem;
use JlacroixDev\PdoRow\Filesystem\LocalFilesystem;

final readonly class GeneratedFileWriter
{
    public function __construct(
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @param GeneratedFile[] $files
     */
    public function write(
        string $directory,
        array $files,
    ): void {
        $this->filesystem->ensureDirectory($directory);
        foreach ($files as $file) {
            $path = "{$directory}/{$file->filename}";
            $this->filesystem->write($path, $file->contents);
        }
    }
}
