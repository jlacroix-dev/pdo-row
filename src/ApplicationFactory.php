<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Command\GenerateCommand;
use JlacroixDev\PdoRow\Command\HelpCommand;
use JlacroixDev\PdoRow\Command\InitCommand;
use JlacroixDev\PdoRow\Command\VersionCommand;
use JlacroixDev\PdoRow\TableInspector\MysqlSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\SqliteSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Template\TemplateRenderer;
use JlacroixDev\PdoRow\Utils\Filesystem;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $tableInspector = new TableInspector([
            new MysqlSchemaInspector(),
            new SqliteSchemaInspector(),
        ]);

        $renderer = new TemplateRenderer();
        $filesystem = new Filesystem();

        $commands = [
            new InitCommand($renderer, $filesystem),
            new GenerateCommand($tableInspector, $renderer, $filesystem),
            new VersionCommand(Version::VERSION),
        ];
        $commands[] = new HelpCommand($commands);

        return new Application($commands);
    }
}