<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow;

use JlacroixDev\PdoRow\Command\GenerateCommand;
use JlacroixDev\PdoRow\Command\GenerateOptionsParser;
use JlacroixDev\PdoRow\Command\HelpCommand;
use JlacroixDev\PdoRow\Command\InitCommand;
use JlacroixDev\PdoRow\Command\VersionCommand;
use JlacroixDev\PdoRow\Filesystem\LocalFilesystem;
use JlacroixDev\PdoRow\Generation\GeneratedFileWriter;
use JlacroixDev\PdoRow\Generation\TableFilter;
use JlacroixDev\PdoRow\TableInspector\MysqlSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\SqliteSchemaInspector;
use JlacroixDev\PdoRow\TableInspector\TableInspector;
use JlacroixDev\PdoRow\Template\TemplateRenderer;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $tableFilter = new TableFilter();
        $tableInspector = new TableInspector([
            new MysqlSchemaInspector(),
            new SqliteSchemaInspector(),
        ]);

        $renderer = new TemplateRenderer();
        $filesystem = new LocalFilesystem();
        $writer = new GeneratedFileWriter($filesystem);

        $commands = [
            new InitCommand($renderer, $filesystem),
            new GenerateCommand(
                new GenerateOptionsParser(),
                $tableFilter,
                $tableInspector,
                $renderer,
                $writer,
                $filesystem
            ),
            new VersionCommand(Version::VERSION),
        ];
        $commands[] = new HelpCommand($commands);

        return new Application($commands);
    }
}
