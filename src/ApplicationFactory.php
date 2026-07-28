<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Command\GenerateCommand;
use JlacroixDev\PdoRow\Command\HelpCommand;
use JlacroixDev\PdoRow\Command\InitCommand;
use JlacroixDev\PdoRow\Command\VersionCommand;
use JlacroixDev\PdoRow\Utils\Filesystem;
use JlacroixDev\PdoRow\Utils\TemplateRenderer;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $renderer = new TemplateRenderer();
        $filesystem = new Filesystem();

        $commands = [
            new InitCommand($renderer, $filesystem),
            new GenerateCommand($renderer, $filesystem),
            new VersionCommand(Version::VERSION),
        ];
        $commands[] = new HelpCommand($commands);

        return new Application($commands);
    }
}