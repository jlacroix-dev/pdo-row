<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use Exception;
use JlacroixDev\PdoRow\Config;
use JlacroixDev\PdoRow\Config\ConfigLoader;
use JlacroixDev\PdoRow\Filesystem\Filesystem;
use PDO;
use PHPUnit\Framework\TestCase;

class ConfigLoaderTest extends TestCase
{
    public function testLoadDefaultConfigFile(): void
    {
        $defaultConfigFile = getcwd() . '/pdo-row.php';

        $pdo = self::createStub(PDO::class);
        $config = new Config($pdo);

        $filesystem = self::createMock(Filesystem::class);
        $filesystem
            ->expects(self::exactly(1))
            ->method('exists')
            ->with($defaultConfigFile)
            ->willReturn(true);
        $filesystem
            ->expects(self::exactly(1))
            ->method('require')
            ->with($defaultConfigFile)
            ->willReturn($config);

        $configLoader = new ConfigLoader($filesystem);

        $configLoader->load(null);
    }

    public function testLoadCustomConfigFile(): void
    {
        $configFile = 'config/pdo-row.php';

        $pdo = self::createStub(PDO::class);
        $config = new Config($pdo);

        $filesystem = self::createMock(Filesystem::class);
        $filesystem
            ->expects(self::exactly(1))
            ->method('exists')
            ->with($configFile)
            ->willReturn(true);
        $filesystem
            ->expects(self::exactly(1))
            ->method('require')
            ->with($configFile)
            ->willReturn($config);

        $configLoader = new ConfigLoader($filesystem);

        $configLoader->load($configFile);
    }

    public function testLoadValidConfiguration(): void
    {
        $pdo = self::createStub(PDO::class);
        $config = new Config($pdo);

        $filesystem = self::createStub(Filesystem::class);
        $filesystem
            ->method('exists')
            ->willReturn(true);
        $filesystem
            ->method('require')
            ->willReturn($config);

        $configLoader = new ConfigLoader($filesystem);

        $configLoader->load(null);

        self::expectNotToPerformAssertions();
    }

    public function testLoadRejectsMissingConfiguration(): void
    {
        $filesystem = self::createStub(Filesystem::class);
        $filesystem
            ->method('exists')
            ->willReturn(false);

        $configLoader = new ConfigLoader($filesystem);

        self::expectException(Exception::class);

        $configLoader->load(null);
    }

    public function testLoadRejectsPhpFilesReturningWrongType(): void
    {
        $config = new class () {
        };

        $filesystem = self::createStub(Filesystem::class);
        $filesystem
            ->method('exists')
            ->willReturn(true);
        $filesystem
            ->method('require')
            ->willReturn($config);

        $configLoader = new ConfigLoader($filesystem);

        self::expectException(Exception::class);

        $configLoader->load(null);
    }
}
