<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use PDO;

final class Config
{
    private PDO $pdo;
    private string $directory = 'src/Repository/PDO/TableRow';
    private string $namespace = 'App\\Repository\\PDO\\TableRow';
    private ?string $phpVersion = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function configure(PDO $pdo): self
    {
        return new Config($pdo);
    }

    public function withDirectory(string $directory): self
    {
        $this->directory = $directory;
        return $this;
    }

    public function withNamespace(string $namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function withPhpVersion(string $phpVersion): self
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getPhpVersion(): string
    {
        return is_null($this->phpVersion)
            ? phpversion()
            : $this->phpVersion;
    }

    public function __toString(): string
    {
        $phpVersion = $this->getPhpVersion();
        return <<<TXT
# Config
Directory: $this->directory
Namespace: $this->namespace
PHP Version: $phpVersion

TXT;
    }
}
